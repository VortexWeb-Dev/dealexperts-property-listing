<?php
require_once __DIR__ . '/crest/crest.php';
require_once __DIR__ . '/crest/settings.php';

function parseLocation($location)
{
    $parts = explode(' - ', $location);

    if (count($parts) !== 4) {
        return "Invalid input format. Expected format: City - Community - Sub Community - Building";
    }

    return array(
        'location' => $location,
        'city' => $parts[0],
        'community' => $parts[1],
        'sub_community' => $parts[2],
        'building' => $parts[3],
    );
}

$result = CRest::call('crm.item.list', [
    'entityTypeId' => BAYUT_LOCATIONS_ENTITY_TYPE_ID
]);

$locations = $result['result']['items'] ?? [];

$data = $_POST;
$location = parseLocation(isset($data['location']) ? $data['location'] : '');

if ($data) {
    CRest::call('crm.item.add', [
        'entityTypeId' => BAYUT_LOCATIONS_ENTITY_TYPE_ID,
        'fields' => [
            'ufCrm54Location' => $data['location'],
            'ufCrm54City' => $location['city'],
            'ufCrm54Community' => $location['community'],
            'ufCrm54SubCommunity' => $location['sub_community'],
            'ufCrm54Building' => $location['building'],
        ]
    ]);

    header('Location: bayut_locations.php');
}
?>

<?php include 'includes/header.php'; ?>

<!-- Location Table and Modal -->
<div class="flex-grow-1" style="height: 100vh; overflow-y: auto;">
    <!-- Fixed Topbar -->
    <?php include 'includes/topbar.php'; ?>

    <!-- Main-container -->
    <div class="container px-3 px-md-5 py-2 py-md-4">
        <h2 class="display-10 fw-bold text-primary container">Buyout Locations</h2>
        <div class="custom-card container mt-4">
            <!-- Add Location Button -->
            <div class="text-end">
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addLocationModal">
                    Add Location
                </button>
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
                                <td><?= $location['ufCrm54Location'] ?></td>
                                <td>
                                    <form action="./delete_location.php" method="POST">
                                        <input type="hidden" name="locationId" value="<?= $location['id'] ?>">
                                        <input type="hidden" name="entityTypeId" value="<?= BAYUT_LOCATIONS_ENTITY_TYPE_ID ?>">
                                        <button class="btn btn-danger" type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/modals/add_bayut_location.php'; ?>

<?php include 'includes/footer.php'; ?>