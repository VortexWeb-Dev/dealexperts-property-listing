<?php
require_once __DIR__ . '/crest/crest.php';
require_once __DIR__ . '/crest/settings.php';

function parseLocation($location)
{
    $parts = explode(' - ', $location);

    // if (count($parts) !== 4) {
    //     return "Invalid input format. Expected format: City - Community - Sub Community - Building";
    // }
    if (count($parts) < 3 || count($parts) > 4) {
        return "Invalid input format. Expected format: City - Community - Sub Community - Building";
    }

    return array(
        'location' => $location,
        'city' => $parts[0] ?? '',
        'community' => $parts[1] ?? '',
        'sub_community' => $parts[2] ?? '',
        'building' => $parts[3] ?? '',
    );
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 50;
$start = ($page - 1) * $limit;

$result = CRest::call('crm.item.list', [
    'entityTypeId' => LOCATIONS_ENTITY_TYPE_ID,
    'start' => $start,
    'limit' => $limit,
]);

$locations = $result['result']['items'] ?? [];
$totalItems = $result['total'] ?? 0;
$totalPages = ceil($totalItems / $limit);

$data = $_POST;

if ($data) {
    $location = parseLocation($data['location']);
    // echo "<pre>";
    // print_r($location);
    // echo "</pre>";
    if (is_array($location)) {
        $response = CRest::call('crm.item.add', [
            'entityTypeId' => LOCATIONS_ENTITY_TYPE_ID,
            'fields' => [
                'ufCrm48Location' => $data['location'],
                'ufCrm48City' => $location['city'],
                'ufCrm48Community' => $location['community'],
                'ufCrm48SubCommunity' => $location['sub_community'],
                'ufCrm48Building' => $location['building'],
            ]
        ]);

        CRest::call('crm.item.add', [
            'entityTypeId' => CITIES_ENTITY_TYPE_ID,
            'fields' => [
                'ufCrm56City' => $location['city'],
            ]
        ]);
        CRest::call('crm.item.add', [
            'entityTypeId' => COMMUNITIES_ENTITY_TYPE_ID,
            'fields' => [
                'ufCrm58Community' => $location['community'],
            ]
        ]);
        CRest::call('crm.item.add', [
            'entityTypeId' => SUB_COMMUNITIES_ENTITY_TYPE_ID,
            'fields' => [
                'ufCrm60SubCommunity' => $location['sub_community'],
            ]
        ]);
        CRest::call('crm.item.add', [
            'entityTypeId' => BUILDINGS_ENTITY_TYPE_ID,
            'fields' => [
                'ufCrm62Building' => $location['building'],
            ]
        ]);

        echo "<script>alert('Location added successfully.'); window.location.href='locations.php';</script>";
    } else {
        echo "<script>alert('Invalid input format. Expected format: City - Community - Sub Community - Building');</script>";
    }
}

?>

<?php include 'includes/header.php'; ?>

<!-- Location Table and Modal -->
<div class="flex-grow-1" style="height: 100vh; overflow-y: auto;">
    <!-- Fixed Topbar -->
    <?php include 'includes/topbar.php'; ?>

    <!-- Main content -->
    <div class="container px-3 px-md-5 py-2 py-md-4">
        <h2 class="display-10 fw-bold text-primary container">Locations</h2>
        <div class="custom-card container mt-4">
            <!-- Add Location Button -->
            <div class="text-end">
                <button type="button" class="btn btn-primary mb-3 mr-3" data-bs-toggle="modal" data-bs-target="#addLocationModal">
                    Add Location
                </button>
                <a href="import_locations.php" class="btn btn-primary mb-3">
                    Import CSV
                </a>
            </div>
            <!-- Locations Table -->
            <div class="table-responsive">
                <table class="table table-borderless text-center">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Location</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="locationTableBody">
                        <?php foreach ($locations as $location) : ?>
                            <tr>
                                <td><?= $location['id'] ?></td>
                                <td><?= $location['ufCrm48Location'] ?></td>
                                <td>
                                    <form action="./delete_location.php" method="POST">
                                        <input type="hidden" name="locationId" value="<?= $location['id'] ?>">
                                        <input type="hidden" name="entityTypeId" value="<?= LOCATIONS_ENTITY_TYPE_ID ?>">
                                        <button class="btn btn-danger" type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <?php include 'includes/pagination.php'; ?>
        </div>
    </div>

    <?php include 'includes//modals/add_location.php'; ?>
</div>

<?php include 'includes/footer.php'; ?>