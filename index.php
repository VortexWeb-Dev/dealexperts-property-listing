<?php
require_once __DIR__ . '/crest/crest.php';
require_once __DIR__ . '/crest/settings.php';
require_once __DIR__ . '/utils/index.php';

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'ALL';
$other_filters = $otherFilters = $_GET ?? null;

$properties = [];
$groups = [];
define('colors', []);
$duplicateIds = [];

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 50;
$start = ($page - 1) * $limit;

$result = CRest::call('crm.item.list', [
    'entityTypeId' => PROPERTY_LISTING_ENTITY_TYPE_ID,
    'start' => $start,
    'limit' => $limit,
    'order' => [
        'id' => 'desc'
    ],
    'filter' => getFilterConditions($filter, $other_filters),
]);

$properties = $result['result']['items'] ?? [];
$totalItems = $result['total'] ?? 0;
$totalPages = ceil($totalItems / $limit);

// Duplicate groups and Indicator logic
foreach ($properties as $index => $property) {
    $key = $property['ufCrm42Community'] . ' - ' . $property['ufCrm42Precinct'] . ' - ' . $property['ufCrm42UnitNumber'];
    if (!isset($groups[$key])) {
        $groups[$key] = [];
    }
    array_push($groups[$key], $index);
}

foreach ($groups as $key => $group) {
    if (count($group) > 1) {
        $bg_color = generateRandomColor();
        $groups[$key]['bg-color'] = $bg_color;
        foreach ($group as $index) {
            array_push($duplicateIds, $properties[$index]['id']);
            $properties[$index]['bg-color'] = $bg_color;
            $properties[$index]['duplicate'] = true;
        }
    } else {
        $groups[$key]['bg-color'] = null;
        $properties[$group[0]]['bg-color'] = null;
        $properties[$group[0]]['duplicate'] = false;
    }
}

$duplicateCount = duplicateGroupCount($groups);

?>

<?php include 'includes/header.php'; ?>

<div class="flex-grow-1" style="height: 100vh; overflow-y: auto;">
    <?php include 'includes/topbar.php'; ?>

    <div class="container px-3 px-md-5 py-2 py-md-4">
        <h2 class="display-10 fw-bold text-primary container">Property Listing</h2>

        <!-- Accordion -->
        <?php include 'includes/index_accordion.php'; ?>

        <!-- Buttons -->
        <div class="d-flex justify-content-between mb-4">
            <div class="mb-3 mb-lg-0">
                <div class="d-flex align-items-center">
                    <!-- dropdown -->
                    <div class="dropdown me-2">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="listingFiltersDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php
                            $filterLabels = [
                                'ALL' => 'All Listings',
                                'DRAFT' => 'Draft',
                                'LIVE' => 'Live',
                                'PENDING' => 'Pending',
                                'ARCHIVED' => 'Archived',
                                'DUPLICATE' => 'Duplicate',
                                'WAITING_PUBLISH' => 'Waiting Publish',
                                'HOT_PROPERTIES' => 'Hot Properties',
                                'PHOTO_REQUEST' => 'Photo Request',
                            ];
                            echo $filterLabels[$filter] ?? 'Select Filter';
                            ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="listingFiltersDropdown">
                            <li><a class="dropdown-item <?php echo $filter == 'ALL' ? 'active' : '' ?>" href="index.php?filter=ALL">All Listings</a></li>
                            <li><a class="dropdown-item <?php echo $filter == 'DRAFT' ? 'active' : '' ?>" href="index.php?filter=DRAFT">Draft</a></li>
                            <li><a class="dropdown-item <?php echo $filter == 'LIVE' ? 'active' : '' ?>" href="index.php?filter=LIVE">Live</a></li>
                            <li><a class="dropdown-item <?php echo $filter == 'PENDING' ? 'active' : '' ?>" href="index.php?filter=PENDING">Pending</a></li>
                            <li><a class="dropdown-item <?php echo $filter == 'ARCHIVED' ? 'active' : '' ?>" href="index.php?filter=ARCHIVED">Archived</a></li>
                            <li><a class="dropdown-item <?php echo $filter == 'DUPLICATE' ? 'active' : '' ?>" href="index.php?filter=DUPLICATE">Duplicate</a></li>
                            <li><a class="dropdown-item <?php echo $filter == 'WAITING_PUBLISH' ? 'active' : '' ?>" href="index.php?filter=WAITING_PUBLISH">Waiting Publish</a></li>
                            <li><a class="dropdown-item <?php echo $filter == 'HOT_PROPERTIES' ? 'active' : '' ?>" href="index.php?filter=HOT_PROPERTIES">Hot Properties</a></li>
                            <li><a class="dropdown-item <?php echo $filter == 'PHOTO_REQUEST' ? 'active' : '' ?>" href="index.php?filter=PHOTO_REQUEST">Photo Request</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-wrap justify-content-lg-end align-items-center gap-2">
                <a href="create_listing.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create Listing
                </a>
                <a class="btn btn-primary" href="./import.php">
                    Import CSV
                </a>
                <a class="btn btn-primary" href="./import_internal_listing_xlsx.php">
                    Import XLSX
                </a>

                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="bulkActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cog me-2"></i>Bulk Actions
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bulkActionsDropdown" style="font-size:small;">
                        <li>
                            <h6 class="dropdown-header">Transfer</h6>
                        </li>
                        <li><button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#transferAgentModal" onclick="selectAndAddPropertiesToAgentTransfer()"><i class="fas fa-user-tie me-2"></i>Transfer to Agent</button></li>
                        <li><button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#transferOwnerModal" onclick="selectAndAddPropertiesToOwnerTransfer()"><i class="fas fa-user me-2"></i>Transfer to Owner</button></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <h6 class="dropdown-header">Publish</h6>
                        </li>
                        <li><button class="dropdown-item" type="button" onclick="publishSelectedProperties()"><i class="fas fa-globe me-2"></i>Publish All</button></li>
                        <li><button class="dropdown-item" type="button" onclick="publishSelectedPropertiesToBayut()"><i class="fas fa-building me-2"></i>Publish To Bayut</button></li>
                        <li><button class="dropdown-item" type="button" onclick="publishSelectedPropertiesToDubizzle()"><i class="fas fa-home me-2"></i>Publish To Dubizzle</button></li>
                        <li><button class="dropdown-item" type="button" onclick="publishSelectedPropertiesToPF()"><i class="fas fa-search me-2"></i>Publish To PF</button></li>
                        <li><button class="dropdown-item" type="button" onclick="unPublishSelectedProperties()"><i class="fas fa-eye-slash me-2"></i>Unpublish</button></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <h6 class="dropdown-header">Unpublish</h6>
                        </li>
                        <li><button class="dropdown-item" type="button" onclick="unPublishFromPropertyFinder()"><i class="fas fa-search me-2"></i>Unpublish from Property Finder</button></li>
                        <li><button class="dropdown-item" type="button" onclick="unPublishFromBayut()"><i class="fas fa-building me-2"></i>Unpublish from Bayut</button></li>
                        <li><button class="dropdown-item" type="button" onclick="unPublishFromDubizzle()"><i class="fas fa-home me-2"></i>Unpublish from Dubizzle</button></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <h6 class="dropdown-header">Import Listings</h6>
                        </li>
                        <li><button class="dropdown-item" type="button" onclick="window.location.href='./import_internal_listing_xlsx.php'"><i class="fas fa-file-excel me-2"></i>Import XLSX</button></li>
                        <li><button class="dropdown-item" type="button" onclick="window.location.href='./import_index.php'"><i class="fas fa-file-csv me-2"></i>Import CSV</button></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><button class="dropdown-item text-danger" type="button" onclick="deleteSelectedProperties()"><i class="fas fa-trash-alt me-2"></i>Delete</button></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="custom-card container mt-4">
            <!-- Property Listing Table -->
            <div class="table-responsive">
                <table class="table table-borderless">
                    <thead class="table-light">
                        <tr>
                            <th style="white-space: nowrap; padding: 10px 20px;">
                                <div class="form-check d-flex justify-content-center align-items-center">
                                    <input class="form-check-input" type="checkbox" id="select-all" onclick="toggleCheckboxes(this)">
                                    <label class="form-check-label" for="select-all"></label>
                                </div>
                            </th>
                            <th style="white-space: nowrap; padding: 10px 20px; color:#334155; font-weight: 600;" scope="col">Actions</th>
                            <th style="white-space: nowrap; padding: 10px 20px; color:#334155; font-weight: 600;" scope="col">Reference</th>
                            <th style="white-space: nowrap; padding: 10px 20px; color:#334155; font-weight: 600;" scope="col">Unit Number</th>
                            <th style="white-space: nowrap; padding: 10px 20px; min-width: 350px; color:#334155; font-weight: 600;" scope="col">Property</th>
                            <th style="white-space: nowrap; padding: 10px 20px; min-width: 150px; color:#334155; font-weight: 600;" scope="col">Details</th>
                            <th style="white-space: nowrap; padding: 10px 20px; color:#334155; font-weight: 600;" scope="col">Type</th>
                            <th style="white-space: nowrap; padding: 10px 20px; min-width: 150px; color:#334155; font-weight: 600;" scope="col">Price</th>
                            <th style="white-space: nowrap; padding: 10px 20px; color:#334155; font-weight: 600;" scope="col">Location</th>
                            <th style="white-space: nowrap; padding: 10px 20px; min-width: 150px; color:#334155; font-weight: 600;" scope="col">Agent</th>
                            <!-- <th>Owner Details</th> -->
                        </tr>
                    </thead>
                    <tbody id="locationTableBody">
                        <?php foreach ($properties as $property) : ?>
                            <tr>
                                <td style="padding: 10px 20px;">
                                    <div class="form-check d-flex align-items-center justify-content-center">
                                        <input class="form-check-input" type="checkbox" name="property_ids[]" value="<?php echo htmlspecialchars($property['id']); ?>">
                                        <label class="form-check-label"></label>
                                    </div>
                                </td>
                                <td style="padding: 10px 20px;" class="d-flex align-items-center gap-2">
                                    <!-- dropdown menu -->
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu shadow absolute z-10" style="max-height: 50vh; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #6B7280 #f9fafb; font-size:medium;">
                                            <li><a class="dropdown-item" href="edit_listing.php?id=<?php echo $property['id']; ?>"><i class="fa-solid fa-edit me-2"></i>Edit</a></li>
                                            <li><a class="dropdown-item" href="view_listing.php?id=<?php echo $property['id']; ?>"><i class="fa-solid fa-eye me-2"></i>View Details</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="copyLink('<?php echo $property['id']; ?>')"><i class="fa-solid fa-link me-2"></i>Copy Link</a></li>
                                            <li><a class="dropdown-item" href="download-property.php?id=<?php echo $property['id']; ?>"><i class="fa-solid fa-download me-2"></i>Download PDF</a></li>
                                            <li><a class="dropdown-item" href="xml.php?propertyId=<?php echo $property['id']; ?>"><i class="fa-solid fa-upload me-2"></i>Publish</a></li>
                                            <li><a class="dropdown-item" href="make-exclusive.php?id=<?php echo $property['id']; ?>"><i class="fa-solid fa-lock me-2"></i>Make Exclusive</a></li>
                                            <li><a class="dropdown-item" href="make-featured.php?id=<?php echo $property['id']; ?>"><i class="fa-solid fa-star me-2"></i>Make Featured</a></li>
                                            <li><a class="dropdown-item" href="make-business-class.php?id=<?php echo $property['id']; ?>"><i class="fa-solid fa-briefcase me-2"></i>Make Business Class</a></li>
                                            <li><a class="dropdown-item" href="duplicate-listing.php?id=<?php echo $property['id']; ?>"><i class="fa-solid fa-copy me-2"></i>Duplicate Listing</a></li>
                                            <li><a class="dropdown-item" href="refresh-listing.php?id=<?php echo $property['id']; ?>"><i class="fa-solid fa-sync me-2"></i>Refresh Listing</a></li>
                                            <li><a class="dropdown-item" href="unpublish.php?id=<?php echo $property['id']; ?>"><i class="fa-solid fa-archive me-2"></i>Unpublish (Archive)</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="copyLinkAsLoggedInAgent('<?php echo $property['id']; ?>')"><i class="fa-solid fa-link me-2"></i>Copy Link as Logged in Agent</a></li>
                                            <li><a class="dropdown-item" href="download-pdf-as-loggedin-agent.php?id=<?php echo $property['id']; ?>"><i class="fa-solid fa-download me-2"></i>Download PDF as Logged in Agent</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item text-danger" href="delete-property.php?id=<?php echo $property['id']; ?>"><i class="fa-solid fa-trash me-2"></i>Delete</a></li>
                                        </ul>
                                    </div>
                                    <!-- duplicate icon -->
                                    <div>
                                        <a class="dropdown-item"><i style="<?= isset($property['duplicate']) && $property['duplicate'] ? "color:" . $property['bg-color'] : "color:" . '#fff'; ?>" class="fa-solid fa-copy"></i></a>
                                    </div>
                                </td>
                                <td><?= !empty($property['ufCrm42ReferenceNumber']) ? $property['ufCrm42ReferenceNumber'] : 'N/A' ?></td>
                                <td><?= $property['ufCrm42UnitNumber'] ?></td>
                                <td style="padding: 10px 20px; min-width: 200px;">
                                    <div class="d-flex align-items-center">
                                        <img src="<?= htmlspecialchars($property['ufCrm42Photos'][0] ?? 'https://via.placeholder.com/60x60') ?>"
                                            class="me-3"
                                            style="width: 60px; height: 60px; object-fit: cover;"
                                            alt="Property Image">
                                        <div>
                                            <h6 class="mb-0"><?= !empty($property['ufCrm42TitleEn']) ? htmlspecialchars($property['ufCrm42TitleEn']) : 'Title' ?></h6>
                                            <small class="text-muted d-inline-block text-truncate" style="max-width: 100px;">
                                                <?= !empty($property['ufCrm42DescriptionEn']) ? htmlspecialchars($property['ufCrm42DescriptionEn']) : 'Description' ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 10px 20px;">
                                    <div class="d-flex flex-column gap-1 no-wrap" style="color: #64748b;">
                                        <span style="font-size:smaller; font-weight: 600;" class="mb-1"><?= htmlspecialchars($property['ufCrm42Size'] ?? 'N/A') ?> sq ft</span>
                                        <div class="d-flex justify-content-start gap-1">
                                            <span class="text-secondary font-size-small me-1"><i class="fa-solid fa-bath me-1"></i><?= htmlspecialchars($property['ufCrm42Bathroom'] ?? 'N/A')  ?></span>
                                            <span class="text-secondary font-size-small"><i class="fa-solid fa-bed me-1"></i><?= htmlspecialchars($property['ufCrm42Bedroom'] ?? 'N/A')  ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 10px 20px;">
                                    <span class="badge bg-primary"><?= !empty($property['ufCrm42PropertyType']) ? htmlspecialchars($property['ufCrm42PropertyType']) : 'N/A' ?></span>
                                    <span class="badge bg-success"><?= !empty($property['ufCrm42Status']) ? htmlspecialchars($property['ufCrm42Status']) : 'N/A' ?></span>
                                </td>
                                <td><?= !empty($property['ufCrm42Price']) ? $property['ufCrm42Price'] : 'N/A' ?></td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold"><?= $property['ufCrm42Community'] ?? 'Title' ?></span>
                                    </div>
                                </td>
                                <td><?= $property['ufCrm42AgentName'] ?></td>

                                <!-- <td>
                                            <div class="d-flex flex-column justify-content-start">
                                                <span class="fw-bold"><?= $property['ufCrm42OwnerName'] ?></span>
                                                <a href="mailto:<?= $property['ufCrm42EmailAddress'] ?>" class="text-muted small"><?= $property['ufCrm42EmailAddress'] ?></a>
                                                <span class="text-muted small"><?= '+' . $property['ufCrm42IsdCode'] . " " . $property['ufCrm42PhoneNumber'] ?></span>
                                            </div>
                                        </td> -->

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php include 'includes/pagination.php'; ?>

        </div>
    </div>

    <!-- Modal (Transfer to Agent) -->
    <?php include 'includes/modals/transfer_to_agent.php'; ?>

    <!-- Modal (Transfer to Owner) -->
    <?php include 'includes/modals/transfer_to_owner.php'; ?>

</div>

<?php include 'includes/footer.php'; ?>