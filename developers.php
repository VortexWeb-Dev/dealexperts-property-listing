<?php
require_once __DIR__ . '/crest/crest.php';
require_once __DIR__ . '/crest/settings.php';

$result = CRest::call('crm.item.list', [
    'entityTypeId' => DEVELOPERS_ENTITY_TYPE_ID
]);
$developers = $result['result']['items'] ?? [];

$data = $_POST;
if ($data) {
    CRest::call('crm.item.add', [
        'entityTypeId' => DEVELOPERS_ENTITY_TYPE_ID,
        'fields' => [
            'ufCrm44DeveloperName' => $data['developer'],
        ]
    ]);

    CRest::call('crm.contact.add', [
        'fields' => [
            'NAME' => $data['developer'],
            'TYPE_ID' => 'SUPPLIER',
        ]
    ]);

    header('Location: developers.php');
}
?>

<?php include 'includes/header.php'; ?>

<div class="flex-grow-1" style="height: 100vh; overflow-y: auto;">
    <!-- Fixed Topbar -->
    <?php include 'includes/topbar.php'; ?>

    <div class="container px-3 px-md-5 py-2 py-md-4">
        <h2 class="display-10 fw-bold text-primary container">Developers</h2>
        <div class="custom-card">
            <div class="container mt-4">
                <div class="text-end">
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addDeveloperModal">
                        Add Developer
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-borderless text-center">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Developer Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="locationTableBody">
                            <?php foreach ($developers as $developer) : ?>
                                <tr>
                                    <td><?= $developer['id'] ?></td>
                                    <td><?= $developer['ufCrm44DeveloperName'] ?></td>
                                    <td>
                                        <form action="./delete_developer.php" method="POST">
                                            <input type="hidden" name="developerId" value="<?= $developer['id'] ?>">
                                            <input type="hidden" name="entityTypeId" value="<?= DEVELOPERS_ENTITY_TYPE_ID ?>">
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

        <?php include 'includes/modals/add_developer.php'; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>