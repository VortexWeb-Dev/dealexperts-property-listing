<?php
require_once __DIR__ . '/crest/crest.php';
require_once __DIR__ . '/crest/settings.php';

session_start();
if (isset($_SESSION['properties'])) {
    $properties = $_SESSION['properties'];
}

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
]);

$properties = $result['result']['items'] ?? [];
$totalItems = $result['total'] ?? 0;
$totalPages = ceil($totalItems / $limit);

foreach ($properties as $index => $property) {
    $key = $property['ufCrm42Community'] . ' - ' . $property['ufCrm42Precinct'] . ' - ' . $property['ufCrm42UnitNumber'];
    if (!isset($groups[$key])) {
        $groups[$key] = [];
    }
    array_push($groups[$key], $index);
}


function generateRandomColor()
{
    do {
        $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    } while (in_array($color, colors));
    $colors[] = $color;
    return $color;
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

function duplicateGroupCount($groups)
{
    $count = 0;
    foreach ($groups as $key => $group) {
        if (count($group) > 1) {
            $count++;
        }
    }
    return $count;
}

$duplicateCount = duplicateGroupCount($groups);

$data = $_POST;

if ($data) {
    $response = CRest::call('crm.item.add', [
        'entityTypeId' => PROPERTY_LISTING_ENTITY_TYPE_ID,
        'fields' => [
            'title' => $data['community'] . ' - ' . $data['unit_number'],
            'ufCrm42Community' => $data['community'],
            'ufCrm42Precinct' => $data['precinct'],
            'ufCrm42UnitNumber' => $data['unit_number'],
            'ufCrm42OwnerName' => [$data['owner_name']],
            'ufCrm42EmailAddress' => $data['email_address'],
            'ufCrm42IsdCode' => $data['isd_code'],
            'ufCrm42PhoneNumber' => $data['phone_number'],
            'ufCrm42AgentName' => 'Laith Maisara Abu Omar',
            'ufCrm42AgentEmail' => 'laith@dere.ae',
            'ufCrm42AgentPhone' => '971545366166',
        ]
    ]);

    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="/styles/app.css">
</head>

<body>
    <div class="d-flex">
        <?php include 'includes/sidebar.php'; ?>

        <div class="flex-grow-1" style="height: 100vh; overflow-y: auto;">
            <?php include 'includes/topbar.php'; ?>

            <div class="container px-3 px-md-5 py-2 py-md-4">
                <h2 class="display-10 fw-bold text-primary container">Property Listing</h2>

                <!-- Accordion -->
                <div class="accordion mb-4" id="accordionExample">
                    <!-- Charts -->
                    <div class="accordion-item border-0 shadow-sm mb-3">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                <i class="fas fa-chart-pie me-2 text-primary"></i>Listing Infographics
                            </button>
                        </h2>

                        <?php

                        // Initialize counts
                        $residentialSales = $residentialRent = $residentialFinder = $residentialBayut = $residentialWebsite = 0;
                        $commercialSales = $commercialRent = $commercialFinder = $commercialBayut = $commercialWebsite = 0;

                        // Iterate through properties to categorize and count
                        foreach ($properties as $property) {
                            if ($property['ufCrm42PropertyType'] === 'residential') {
                                // Count Residential properties by source
                                $residentialSales += ($property['sourceId'] === 'CALL') ? 1 : 0;
                                $residentialRent += ($property['sourceId'] === 'RENT') ? 1 : 0;
                                $residentialFinder += ($property['ufCrm42PfEnable'] === 'Y') ? 1 : 0;
                                $residentialBayut += ($property['ufCrm42BayutEnable'] === 'Y') ? 1 : 0;
                                $residentialWebsite += ($property['ufCrm42WebsiteEnable'] === 'Y') ? 1 : 0;
                            } elseif ($property['ufCrm42PropertyType'] === 'commercial') {
                                // Count Commercial properties by source
                                $commercialSales += ($property['sourceId'] === 'CALL') ? 1 : 0;
                                $commercialRent += ($property['sourceId'] === 'RENT') ? 1 : 0;
                                $commercialFinder += ($property['ufCrm42PfEnable'] === 'Y') ? 1 : 0;
                                $commercialBayut += ($property['ufCrm42BayutEnable'] === 'Y') ? 1 : 0;
                                $commercialWebsite += ($property['ufCrm42WebsiteEnable'] === 'Y') ? 1 : 0;
                            }
                        }
                        ?>

                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body bg-white">
                                <div class="row">
                                    <div class="col-md-6 mb-4 mb-md-0">
                                        <h3 class="text-center mb-4 text-primary">Residential</h3>
                                        <div class="d-flex justify-content-center">
                                            <canvas id="leftChart" width="250" height="250"></canvas>
                                            <div class="ms-4 d-flex align-items-center">
                                                <ul class="list-unstyled">
                                                    <li class="mb-2"><span class="badge rounded-pill" style="background-color: rgba(54, 162, 235, 0.6);"><?= $residentialSales ?></span> Sales</li>
                                                    <li class="mb-2"><span class="badge rounded-pill" style="background-color: rgba(75, 192, 192, 0.6);"><?= $residentialRent ?></span> Rent</li>
                                                    <li class="mb-2"><span class="badge rounded-pill" style="background-color: rgba(153, 102, 255, 0.6);"><?= $residentialFinder ?></span> Property Finder</li>
                                                    <li class="mb-2"><span class="badge rounded-pill" style="background-color: rgba(255, 159, 64, 0.6);"><?= $residentialBayut ?></span> Bayut</li>
                                                    <li class="mb-2"><span class="badge rounded-pill" style="background-color: rgba(255, 205, 86, 0.6);"><?= $residentialWebsite ?></span> Website</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h3 class="text-center mb-4 text-primary">Commercial</h3>
                                        <div class="d-flex justify-content-center">
                                            <canvas id="rightChart" width="250" height="250"></canvas>
                                            <div class="ms-4 d-flex align-items-center">
                                                <ul class="list-unstyled">
                                                    <li class="mb-2"><span class="badge rounded-pill" style="background-color: rgba(255, 99, 132, 0.6);"><?= $commercialSales ?></span> Sales</li>
                                                    <li class="mb-2"><span class="badge rounded-pill" style="background-color: rgba(54, 162, 235, 0.6);"><?= $commercialRent ?></span> Rent</li>
                                                    <li class="mb-2"><span class="badge rounded-pill" style="background-color: rgba(255, 206, 86, 0.6);"><?= $commercialFinder ?></span> Property Finder</li>
                                                    <li class="mb-2"><span class="badge rounded-pill" style="background-color: rgba(75, 192, 192, 0.6);"><?= $commercialBayut ?></span> Bayut</li>
                                                    <li class="mb-2"><span class="badge rounded-pill" style="background-color: rgba(153, 102, 255, 0.6);"><?= $commercialWebsite ?></span> Website</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- filter  -->
                    <div class="accordion-item border-0 shadow-sm">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <i class="fas fa-filter me-2 text-primary"></i>Filters
                            </button>
                        </h2>

                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                            <div class="accordion-body bg-white">
                                <div class="modal-content">

                                    <?php
                                    $locations_res = CRest::call('crm.item.list', ['entityTypeId' => LOCATIONS_ENTITY_TYPE_ID]);
                                    $locations = $locations_res['result']['items'] ?? [];

                                    $developers_res = CRest::call('crm.item.list', ['entityTypeId' => DEVELOPERS_ENTITY_TYPE_ID]);
                                    $developers = $developers_res['result']['items'] ?? [];

                                    $agents_res = CRest::call('crm.item.list', ['entityTypeId' => LISTING_AGENTS_ENTITY_TYPE_ID]);
                                    $agents = $agents_res['result']['items'] ?? [];

                                    $landlords_res = CRest::call('crm.item.list', ['entityTypeId' => LANDLORDS_ENTITY_TYPE_ID]);
                                    $landlords = $landlords_res['result']['items'] ?? [];

                                    $property_types = array(
                                        "AP" => "Apartment / Flat",
                                        "BW" => "Bungalow",
                                        "CD" => "Compound",
                                        "DX" => "Duplex",
                                        "FF" => "Full floor",
                                        "HF" => "Half floor",
                                        "LP" => "Land / Plot",
                                        "PH" => "Penthouse",
                                        "TH" => "Townhouse",
                                        "VH" => "Villa / House",
                                        "WB" => "Whole Building",
                                        "HA" => "Short Term / Hotel Apartment",
                                        "LC" => "Labor camp",
                                        "BU" => "Bulk units",
                                        "WH" => "Warehouse",
                                        "FA" => "Factory",
                                        "OF" => "Office space",
                                        "RE" => "Retail",
                                        "LP" => "Plot",
                                        "SH" => "Shop",
                                        "SR" => "Show Room",
                                        "SA" => "Staff Accommodation"
                                    );

                                    ?>
                                    <div class="modal-body">
                                        <form id="filterForm" method="GET" action="index.php">
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label for="refId" class="form-label">Ref. ID</label>
                                                    <input type="text" id="refId" name="refId" class="form-control">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="community" class="form-label">Community</label>
                                                    <select id="community" name="community" class="form-select">
                                                        <?php
                                                        foreach ($locations as $location) {
                                                            echo '<option value="' . $location['ufCrm48Community'] . '">' . $location['ufCrm48Community'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="subCommunity" class="form-label">Sub Community</label>
                                                    <select id="subCommunity" name="subCommunity" class="form-select">
                                                        <?php
                                                        foreach ($locations as $location) {
                                                            echo '<option value="' . $location['ufCrm48SubCommunity'] . '">' . $location['ufCrm48SubCommunity'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="building" class="form-label">Building</label>
                                                    <select id="building" name="building" class="form-select">
                                                        <?php
                                                        foreach ($locations as $location) {
                                                            echo '<option value="' . $location['ufCrm48Building'] . '">' . $location['ufCrm48Building'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row g-3 mt-3">
                                                <div class="col-md-3">
                                                    <label for="unitNo" class="form-label">Unit No.</label>
                                                    <input type="text" id="unitNo" name="unitNo" class="form-control">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="permit" class="form-label">Permit # or DMTC #</label>
                                                    <input type="text" id="permit" name="permit" class="form-control">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="listingOwner" class="form-label">Listing Owner</label>
                                                    <input type="text" id="listingOwner" name="listingOwner" class="form-control">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="listingTitle" class="form-label">Listing Title</label>
                                                    <input type="text" id="listingTitle" name="listingTitle" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row g-3 mt-3">
                                                <div class="col-md-3">
                                                    <label for="category" class="form-label">Category</label>
                                                    <input type="text" id="category" name="category" class="form-control">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="propertyType" class="form-label">Property Type</label>
                                                    <select id="propertyType" name="propertyType" class="form-select">

                                                        <?php foreach ($property_types as $code => $name): ?>
                                                            <option value="<?= $code ?>"><?= $name ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="saleRent" class="form-label">Sale/ Rent</label>
                                                    <input type="text" id="saleRent" name="saleRent" class="form-control">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="listingAgents" class="form-label">Property Listing</label>
                                                    <select id="listingAgents" name="listingAgents" class="form-select">
                                                        <?php
                                                        foreach ($agents as $agent) {
                                                            echo '<option value="' . $agent['ufCrm46AgentName'] . '">' . $agent['ufCrm46AgentName'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row g-3 mt-3">
                                                <div class="col-md-3">
                                                    <label for="landlord" class="form-label">Landlord</label>
                                                    <select id="landlord" name="landlord" class="form-select">
                                                        <?php
                                                        foreach ($landlords as $landlord) {
                                                            echo '<option value="' . $landlord['ufCrm50LandlordName'] . '">' . $landlord['ufCrm50LandlordName'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="landlordEmail" class="form-label">Landlord Email</label>
                                                    <input type="email" id="landlordEmail" name="landlordEmail" class="form-control">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="landlordPhone" class="form-label">Landlord Phone</label>
                                                    <input type="text" id="landlordPhone" name="landlordPhone" class="form-control">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="bedrooms" class="form-label">Bedrooms <span id="selectedBedrooms"></span></label>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2">1</span>
                                                        <input type="range" id="bedrooms" name="bedrooms" class="form-range flex-grow-1" min="1" max="7">
                                                        <span class="ms-2">7</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-3 mt-3">
                                                <div class="col-md-3">
                                                    <label for="developers" class="form-label">Developers</label>
                                                    <select id="developers" name="developers" class="form-select">
                                                        <?php
                                                        foreach ($developers as $developer) {
                                                            echo '<option value="' . $developer['ufCrm44DeveloperName'] . '">' . $developer['ufCrm44DeveloperName'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="price" class="form-label">Price <span id="selectedPrice"></span></label>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2">0</span>
                                                        <input type="range" id="price" name="price" class="form-range flex-grow-1" min="0" max="479999000">
                                                        <span class="ms-2">479999000</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="portals" class="form-label">Portals</label>
                                                    <input type="text" id="portals" name="portals" class="form-control">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer d-flex gap-2">
                                        <button type="reset" form="filterForm" class="btn btn-outline-secondary">Reset</button>
                                        <button type="submit" form="filterForm" class="btn btn-primary">Apply</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between mb-4">
                    <div class="mb-3 mb-lg-0">
                        <div class="d-flex align-items-center">
                            <!-- dropdown -->
                            <div class="dropdown me-2">
                                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="listingFiltersDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php
                                    $filterLabels = [
                                        'all' => 'All Listings',
                                        'draft' => 'Draft',
                                        'live' => 'Live',
                                        'pending' => 'Pending',
                                        'archived' => 'Archived',
                                        'duplicate' => 'Duplicate',
                                        'waiting_publish' => 'Waiting Publish',
                                        'hot_properties' => 'Hot Properties',
                                        'photo_request' => 'Photo Request',
                                    ];
                                    echo $filterLabels[$filter] ?? 'Select Filter';
                                    ?>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="listingFiltersDropdown">
                                    <li><a class="dropdown-item <?php echo $filter == 'all' ? 'active' : '' ?>" href="index.php?filter=all">All Listings</a></li>
                                    <li><a class="dropdown-item <?php echo $filter == 'draft' ? 'active' : '' ?>" href="index.php?filter=draft">Draft</a></li>
                                    <li><a class="dropdown-item <?php echo $filter == 'live' ? 'active' : '' ?>" href="index.php?filter=live">Live</a></li>
                                    <li><a class="dropdown-item <?php echo $filter == 'pending' ? 'active' : '' ?>" href="index.php?filter=pending">Pending</a></li>
                                    <li><a class="dropdown-item <?php echo $filter == 'archived' ? 'active' : '' ?>" href="index.php?filter=archived">Archived</a></li>
                                    <li><a class="dropdown-item <?php echo $filter == 'duplicate' ? 'active' : '' ?>" href="index.php?filter=duplicate">Duplicate</a></li>
                                    <li><a class="dropdown-item <?php echo $filter == 'waiting_publish' ? 'active' : '' ?>" href="index.php?filter=waiting_publish">Waiting Publish</a></li>
                                    <li><a class="dropdown-item <?php echo $filter == 'hot_properties' ? 'active' : '' ?>" href="index.php?filter=hot_properties">Hot Properties</a></li>
                                    <li><a class="dropdown-item <?php echo $filter == 'photo_request' ? 'active' : '' ?>" href="index.php?filter=photo_request">Photo Request</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap justify-content-lg-end align-items-center gap-2">
                        <a href="create_listing.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create Listing
                        </a>
                        <a class="btn btn-primary" href="./import_index.php">
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
                    <!-- Add Location Button -->
                    <!-- <div class="d-flex justify-content-end items-center">
                        <div class="mb-3 flex gap-3">
                            <button type="button" class="btn btn-primary mr-3" data-bs-toggle="modal" data-bs-target="#addListingModal">
                                <i class="fas fa-plus"></i> Create Listing
                            </button>

                        </div>
                    </div> -->

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
                                                <span class="text-success small"><?= $property['ufCrm42Price'] ?? '0' . " AED" ?></span>
                                            </div>
                                        </td>
                                        <td><?= $property['ufCrm42AgentName'] ?></td>

                                        <!-- <td>
                                            <div class="d-flex flex-column justify-content-start">
                                                <span class="fw-bold"><?= $property['ufCrm42OwnerName'][0] ?></span>
                                                <a href="mailto:<?= $property['ufCrm42EmailAddress'] ?>" class="text-muted small"><?= $property['ufCrm42EmailAddress'] ?></a>
                                                <span class="text-muted small"><?= '+' . $property['ufCrm42IsdCode'] . " " . $property['ufCrm42PhoneNumber'] ?></span>
                                            </div>
                                        </td> -->

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination mt-4">
                        <?php if ($totalPages > 1): ?>
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    <!-- Previous Button -->
                                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                        <a class="page-link" href="?page=<?= max(1, $page - 1) ?>" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>

                                    <?php
                                    // Determine range of page numbers to show
                                    $range = 2; // Number of pages to show around the current page
                                    $showPages = [1, $totalPages]; // Always show first and last page

                                    for ($i = max(2, $page - $range); $i <= min($totalPages - 1, $page + $range); $i++) {
                                        $showPages[] = $i;
                                    }

                                    // Add pages at the start and end within the range of pages shown
                                    $showPages = array_unique(array_merge($showPages, range(1, min(3, $totalPages)), range(max(1, $totalPages - 2), $totalPages)));

                                    // Loop through all pages and display only the relevant ones
                                    for ($i = 1; $i <= $totalPages; $i++):
                                        if (in_array($i, $showPages)):
                                    ?>
                                            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                            </li>
                                        <?php elseif ($i < $page && !in_array($i + 1, $showPages)): ?>
                                            <li class="page-item disabled"><span class="page-link">...</span></li>
                                            <?php $i = $page - $range - 1; // Skip pages until the current page range 
                                            ?>
                                        <?php elseif ($i > $page && !in_array($i - 1, $showPages)): ?>
                                            <li class="page-item disabled"><span class="page-link">...</span></li>
                                            <?php $i = $totalPages - 2; // Skip pages until the end 
                                            ?>
                                        <?php endif; ?>
                                    <?php endfor; ?>

                                    <!-- Next Button -->
                                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                        <a class="page-link" href="?page=<?= min($totalPages, $page + 1) ?>" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

            <!-- Modal for Adding Listing Agent -->
            <div class="modal fade" id="addListingModal" tabindex="-1" aria-labelledby="addListingModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addListingModalLabel">Add Listing</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addListingForm" method="post" action="./index.php">
                                <div class="mb-3 row">
                                    <div class="col">
                                        <label for="community" class="form-label">Community</label>
                                        <input type="text" class="form-control" id="community" name="community" required>
                                    </div>
                                    <div class="col">
                                        <label for="precinct" class="form-label">Precinct</label>
                                        <input type="text" class="form-control" id="precinct" name="precinct" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <div class="col">
                                        <label for="unit_number" class="form-label">Unit Number</label>
                                        <input type="text" class="form-control" id="unit_number" name="unit_number" required>
                                    </div>
                                    <div class="col">
                                        <label for="owner_name" class="form-label">Owner Name</label>
                                        <input type="text" class="form-control" id="owner_name" name="owner_name" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <div class="col">
                                        <label for="email_address" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email_address" name="email_address" required>
                                    </div>
                                    <div class="col">
                                        <label for="isd_code" class="form-label">ISD Code</label>
                                        <input type="text" class="form-control" id="isd_code" name="isd_code" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" form="addListingForm">Add Listing</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal (Transfer to Agent) -->
            <div class="modal fade" id="transferAgentModal" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="transferModalLabel">Transfer Property to Agent</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Form inside modal -->
                            <form id="transferAgentForm" method="POST" action="transfer_agent.php">
                                <input type="hidden" id="transferAgentPropertyIds" name="transferAgentPropertyIds">
                                <div class="form-group">
                                    <label for="agentSelect">Select Listing Agent</label>
                                    <select class="form-control" id="agentSelect" name="agent_id" required>
                                        <?php
                                        // Fetch and display listing agents
                                        $agents_result = CRest::call('crm.item.list', ['entityTypeId' => LISTING_AGENTS_ENTITY_TYPE_ID]);
                                        $listing_agents = $agents_result['result']['items'] ?? [];

                                        foreach ($listing_agents as $agent) {
                                            echo '<option value="' . htmlspecialchars($agent['id']) . '">' . htmlspecialchars($agent['ufCrm46AgentName']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Transfer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal (Transfer to Owner) -->
            <div class="modal fade" id="transferOwnerModal" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="transferModalLabel">Transfer Property to Owner</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Form inside modal -->
                            <form id="transferOwnerForm" method="POST" action="transfer_owner.php">
                                <input type="hidden" id="transferOwnerPropertyIds" name="transferOwnerPropertyIds">
                                <div class="form-group">
                                    <label for="ownerSelect">Select Listing Owner</label>
                                    <select class="form-control" id="ownerSelect" name="owner_id" required>
                                        <?php
                                        // Fetch and display listing owners
                                        $owners_result = CRest::call('crm.item.list', ['entityTypeId' => LANDLORDS_ENTITY_TYPE_ID]);
                                        $listing_owners = $owners_result['result']['items'] ?? [];

                                        foreach ($listing_owners as $owner) {
                                            echo '<option value="' . htmlspecialchars($owner['id']) . '">' . htmlspecialchars($owner['ufCrm50LandlordName']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Transfer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Modal -->
            <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="filterModalLabel">Filters</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <?php
                        $locations_res = CRest::call('crm.item.list', ['entityTypeId' => LOCATIONS_ENTITY_TYPE_ID]);
                        $locations = $locations_res['result']['items'] ?? [];

                        $developers_res = CRest::call('crm.item.list', ['entityTypeId' => DEVELOPERS_ENTITY_TYPE_ID]);
                        $developers = $developers_res['result']['items'] ?? [];

                        $agents_res = CRest::call('crm.item.list', ['entityTypeId' => LISTING_AGENTS_ENTITY_TYPE_ID]);
                        $agents = $agents_res['result']['items'] ?? [];

                        $landlords_res = CRest::call('crm.item.list', ['entityTypeId' => LANDLORDS_ENTITY_TYPE_ID]);
                        $landlords = $landlords_res['result']['items'] ?? [];

                        $property_types = array(
                            "AP" => "Apartment / Flat",
                            "BW" => "Bungalow",
                            "CD" => "Compound",
                            "DX" => "Duplex",
                            "FF" => "Full floor",
                            "HF" => "Half floor",
                            "LP" => "Land / Plot",
                            "PH" => "Penthouse",
                            "TH" => "Townhouse",
                            "VH" => "Villa / House",
                            "WB" => "Whole Building",
                            "HA" => "Short Term / Hotel Apartment",
                            "LC" => "Labor camp",
                            "BU" => "Bulk units",
                            "WH" => "Warehouse",
                            "FA" => "Factory",
                            "OF" => "Office space",
                            "RE" => "Retail",
                            "LP" => "Plot",
                            "SH" => "Shop",
                            "SR" => "Show Room",
                            "SA" => "Staff Accommodation"
                        );

                        ?>
                        <div class="modal-body">
                            <form id="filterForm" method="GET" action="index.php">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label for="refId" class="form-label">Ref. ID</label>
                                        <input type="text" id="refId" name="refId" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="community" class="form-label">Community</label>
                                        <select id="community" name="community" class="form-select">
                                            <?php
                                            foreach ($locations as $location) {
                                                echo '<option value="' . $location['ufCrm48Community'] . '">' . $location['ufCrm48Community'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="subCommunity" class="form-label">Sub Community</label>
                                        <select id="subCommunity" name="subCommunity" class="form-select">
                                            <?php
                                            foreach ($locations as $location) {
                                                echo '<option value="' . $location['ufCrm48SubCommunity'] . '">' . $location['ufCrm48SubCommunity'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="building" class="form-label">Building</label>
                                        <select id="building" name="building" class="form-select">
                                            <?php
                                            foreach ($locations as $location) {
                                                echo '<option value="' . $location['ufCrm48Building'] . '">' . $location['ufCrm48Building'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row g-3 mt-3">
                                    <div class="col-md-3">
                                        <label for="unitNo" class="form-label">Unit No.</label>
                                        <input type="text" id="unitNo" name="unitNo" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="permit" class="form-label">Permit # or DMTC #</label>
                                        <input type="text" id="permit" name="permit" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="listingOwner" class="form-label">Listing Owner</label>
                                        <input type="text" id="listingOwner" name="listingOwner" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="listingTitle" class="form-label">Listing Title</label>
                                        <input type="text" id="listingTitle" name="listingTitle" class="form-control">
                                    </div>
                                </div>
                                <div class="row g-3 mt-3">
                                    <div class="col-md-3">
                                        <label for="category" class="form-label">Category</label>
                                        <input type="text" id="category" name="category" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="propertyType" class="form-label">Property Type</label>
                                        <select id="propertyType" name="propertyType" class="form-select">

                                            <?php foreach ($property_types as $code => $name): ?>
                                                <option value="<?= $code ?>"><?= $name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="saleRent" class="form-label">Sale/ Rent</label>
                                        <input type="text" id="saleRent" name="saleRent" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="listingAgents" class="form-label">Property Listing</label>
                                        <select id="listingAgents" name="listingAgents" class="form-select">
                                            <?php
                                            foreach ($agents as $agent) {
                                                echo '<option value="' . $agent['ufCrm46AgentName'] . '">' . $agent['ufCrm46AgentName'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row g-3 mt-3">
                                    <div class="col-md-3">
                                        <label for="landlord" class="form-label">Landlord</label>
                                        <select id="landlord" name="landlord" class="form-select">
                                            <?php
                                            foreach ($landlords as $landlord) {
                                                echo '<option value="' . $landlord['ufCrm50LandlordName'] . '">' . $landlord['ufCrm50LandlordName'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="landlordEmail" class="form-label">Landlord Email</label>
                                        <input type="email" id="landlordEmail" name="landlordEmail" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="landlordPhone" class="form-label">Landlord Phone</label>
                                        <input type="text" id="landlordPhone" name="landlordPhone" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="bedrooms" class="form-label">Bedrooms <span id="selectedBedrooms"></span></label>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">1</span>
                                            <input type="range" id="bedrooms" name="bedrooms" class="form-range flex-grow-1" min="1" max="7">
                                            <span class="ms-2">7</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3 mt-3">
                                    <div class="col-md-3">
                                        <label for="developers" class="form-label">Developers</label>
                                        <select id="developers" name="developers" class="form-select">
                                            <?php
                                            foreach ($developers as $developer) {
                                                echo '<option value="' . $developer['ufCrm44DeveloperName'] . '">' . $developer['ufCrm44DeveloperName'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="price" class="form-label">Price <span id="selectedPrice"></span></label>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">0</span>
                                            <input type="range" id="price" name="price" class="form-range flex-grow-1" min="0" max="479999000">
                                            <span class="ms-2">479999000</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="portals" class="form-label">Portals</label>
                                        <input type="text" id="portals" name="portals" class="form-control">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="reset" form="filterForm" class="btn btn-outline-secondary">Reset</button>
                            <button type="submit" form="filterForm" class="btn btn-primary">Apply</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="./js/script.js"></script>
    <script>
        function deleteSelectedProperties() {
            var checkboxes = document.querySelectorAll('input[name="property_ids[]"]:checked');
            var propertyIds = Array.from(checkboxes).map(checkbox => checkbox.value);

            if (propertyIds.length === 0) {
                alert('No properties selected');
                return;
            }

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log('Response:', xhr.responseText);
                    location.reload();
                } else {
                    console.error('Error:', xhr.statusText);
                }
            };
            xhr.send('property_ids=' + encodeURIComponent(JSON.stringify(propertyIds)));
        }

        function publishSelectedProperties() {
            var checkboxes = document.querySelectorAll('input[name="property_ids[]"]:checked');
            var propertyIds = Array.from(checkboxes).map(checkbox => checkbox.value);

            if (propertyIds.length === 0) {
                alert('No properties selected');
                return;
            }

            var url = `xml.php?property_ids=${encodeURIComponent(JSON.stringify(propertyIds))}`;
            window.location.href = url;
        }

        function exportProperties() {
            var checkboxes = document.querySelectorAll('input[name="property_ids[]"]:checked');
            var propertyIds = Array.from(checkboxes).map(checkbox => checkbox.value);

            if (propertyIds.length === 0) {
                alert('No properties selected');
                return;
            }

            document.getElementById('exportForm').submit();
        }

        function publishSelectedPropertiesToBayut() {
            var checkboxes = document.querySelectorAll('input[name="property_ids[]"]:checked');
            var propertyIds = Array.from(checkboxes).map(checkbox => checkbox.value);

            if (propertyIds.length === 0) {
                alert('No properties selected');
                return;
            }

            var url = `xml.php?platform=bayut&property_ids=${encodeURIComponent(JSON.stringify(propertyIds))}`;
            window.location.href = url;
        }

        function publishSelectedPropertiesToDubizzle() {
            var checkboxes = document.querySelectorAll('input[name="property_ids[]"]:checked');
            var propertyIds = Array.from(checkboxes).map(checkbox => checkbox.value);

            if (propertyIds.length === 0) {
                alert('No properties selected');
                return;
            }

            var url = `xml.php?platform=dubizzle&property_ids=${encodeURIComponent(JSON.stringify(propertyIds))}`;
            window.location.href = url;
        }

        function publishSelectedPropertiesToPF() {
            var checkboxes = document.querySelectorAll('input[name="property_ids[]"]:checked');
            var propertyIds = Array.from(checkboxes).map(checkbox => checkbox.value);

            if (propertyIds.length === 0) {
                alert('No properties selected');
                return;
            }

            var url = `xml.php?property_ids=${encodeURIComponent(JSON.stringify(propertyIds))}`;
            window.location.href = url;
        }

        function unPublishSelectedProperties() {
            var checkboxes = document.querySelectorAll('input[name="property_ids[]"]:checked');
            var propertyIds = Array.from(checkboxes).map(checkbox => checkbox.value);

            if (propertyIds.length === 0) {
                alert('No properties selected');
                return;
            }

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'unpublish.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log('Response:', xhr.responseText);
                    alert('Success: Properties unpublished successfully');
                } else {
                    console.error('Error:', xhr.statusText);
                }
            };
            xhr.send('property_ids=' + encodeURIComponent(JSON.stringify(propertyIds)));
        }

        function transferSelectedPropertiesToAgent() {
            var checkboxes = document.querySelectorAll('input[name="property_ids[]"]:checked');
            var propertyIds = Array.from(checkboxes).map(checkbox => checkbox.value);

            if (propertyIds.length === 0) {
                alert('No properties selected');
                return;
            }

            var queryParams = new URLSearchParams({
                property_ids: propertyIds.join(',')
            });
            window.location.href = 'transfer_agent.php?' + queryParams.toString();
        }

        function selectAndAddPropertiesToAgentTransfer() {
            var checkboxes = document.querySelectorAll('input[name="property_ids[]"]:checked');
            var propertyIds = Array.from(checkboxes).map(checkbox => checkbox.value);
            document.getElementById('transferAgentPropertyIds').value = propertyIds.join(',');
        }

        function selectAndAddPropertiesToOwnerTransfer() {
            var checkboxes = document.querySelectorAll('input[name="property_ids[]"]:checked');
            var propertyIds = Array.from(checkboxes).map(checkbox => checkbox.value);
            document.getElementById('transferOwnerPropertyIds').value = propertyIds.join(',');
        }

        const bedroomsInput = document.getElementById('bedrooms');
        bedroomsInput.addEventListener('change', function() {
            const selectedBedrooms = this.value;
            document.getElementById('selectedBedrooms').innerText = ' (' + selectedBedrooms + ')';
        });

        const priceInput = document.getElementById('price');
        priceInput.addEventListener('change', function() {
            const selectedPrice = this.value;
            document.getElementById('selectedPrice').innerText = ' (' + selectedPrice + ')';
        });

        function copyLink(propertyId) {
            var url = `${window.location.origin}/projects/property-listing/view_listing.php?id=${propertyId}`;
            navigator.clipboard.writeText(url).then(function() {
                alert('Link copied to clipboard');
            }).catch(function(err) {
                console.error('Failed to copy the link: ', err);
            });
        }

        function toggleCheckboxes(source) {
            const checkboxes = document.querySelectorAll('input[name="property_ids[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = source.checked;
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarClose = document.getElementById('sidebarClose');
            const mainContent = document.querySelector('.flex-grow-1');

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.add('active');
                sidebarToggle.style.left = '270px';
            });

            sidebarClose.addEventListener('click', function() {
                sidebar.classList.remove('active');
                sidebarToggle.style.left = '20px';
            });

            document.addEventListener('click', function(event) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnToggleButton = sidebarToggle.contains(event.target);

                if (!isClickInsideSidebar && !isClickOnToggleButton && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                    sidebarToggle.style.left = '20px';
                }
            });

            const currentPage = window.location.pathname.split("/").pop();
            const navLinks = document.querySelectorAll('#sidebar .nav-link');
            navLinks.forEach(link => {
                if (link.getAttribute('href').includes(currentPage)) {
                    link.classList.add('active');
                }
            });
        });
    </script>

</body>

</html>