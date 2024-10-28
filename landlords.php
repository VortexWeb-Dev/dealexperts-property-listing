<?php
require_once __DIR__ . '/crest/crest.php';
require_once __DIR__ . '/crest/settings.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 50;
$start = ($page - 1) * $limit;

$result = CRest::call('crm.item.list', [
    'entityTypeId' => LANDLORDS_ENTITY_TYPE_ID,
    'start' => $start,
    'limit' => $limit,
    'order' => [
        'id' => 'desc'
    ]
]);

$landlords = $result['result']['items'] ?? [];
$totalItems = $result['total'] ?? 0;
$totalPages = ceil($totalItems / $limit);

$data = $_POST;

if ($data) {
    $response = CRest::call('crm.item.add', [
        'entityTypeId' => LANDLORDS_ENTITY_TYPE_ID,
        'fields' => [
            'ufCrm50LandlordName' => $data['name'],
            'ufCrm50LandlordEmail' => $data['email'],
            'ufCrm50LandlordMobile' => $data['mobile'],
        ]
    ]);

    $contact_res = CRest::call('crm.contact.add', [
        'fields' => [
            'NAME' => $data['name'],
            'TYPE_ID' => 'UC_1KUBSF',
            'EMAIL' => [['VALUE' => $data['email'], 'VALUE_TYPE' => 'WORK']],
            'PHONE' => [['VALUE' => $data['mobile'], 'VALUE_TYPE' => 'WORK']],
        ]
    ]);

    header('Location: landlords.php');
}
?>

<?php include 'includes/header.php'; ?>

<!-- Landlords Table and Modal -->
<div class="flex-grow-1" style="height: 100vh; overflow-y: auto;">
    <!-- Fixed Topbar -->
    <?php include 'includes/topbar.php'; ?>

    <div class="container px-3 px-md-5 py-2 py-md-4">
        <h2 class="display-10 fw-bold text-primary container">Landlords</h2>
        <div class="custom-card container mt-4">
            <!-- Add Landlords Button -->
            <div class="text-end">
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addLandlordModal">
                    Add Landlord
                </button>
            </div>
            <!-- landlords Table -->
            <div class="table-responsive">
                <table class="table table-borderless text-center">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="locationTableBody">
                        <?php foreach ($landlords as $landlord) : ?>
                            <tr>
                                <td><?= $landlord['id'] ?></td>
                                <td><?= $landlord['ufCrm50LandlordName'] ?></td>
                                <td><?= $landlord['ufCrm50LandlordEmail'] ?></td>
                                <td><?= $landlord['ufCrm50LandlordMobile'] ?></td>
                                <td>
                                    <form action="./delete_landlord.php" method="POST">
                                        <input type="hidden" name="landlordId" value="<?= $landlord['id'] ?>">
                                        <input type="hidden" name="entityTypeId" value="<?= LANDLORDS_ENTITY_TYPE_ID ?>">
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
</div>

<?php include 'includes//modals/add_landlord.php'; ?>



<?php include 'includes/footer.php'; ?>