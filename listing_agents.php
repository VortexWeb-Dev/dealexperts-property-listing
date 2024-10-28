<?php
require_once __DIR__ . '/crest/crest.php';
require_once __DIR__ . '/crest/settings.php';

$result = CRest::call('crm.item.list', [
    'entityTypeId' => LISTING_AGENTS_ENTITY_TYPE_ID
]);
$listing_agents = $result['result']['items'] ?? [];

$data = $_POST;
if ($data) {
    CRest::call('crm.item.add', [
        'entityTypeId' => LISTING_AGENTS_ENTITY_TYPE_ID,
        'fields' => [
            'ufCrm46AgentName' => $data['name'],
            'ufCrm46AgentEmail' => $data['email'],
            'ufCrm46AgentMobile' => $data['mobile'],
            'ufCrm46AgentDesignation' => $data['designation'],
            'ufCrm46AgentRole' => $data['role'],
        ]
    ]);

    $contact_res = CRest::call('crm.contact.add', [
        'fields' => [
            'NAME' => $data['name'],
            'TYPE_ID' => '1',
            'EMAIL' => [['VALUE' => $data['email'], 'VALUE_TYPE' => 'WORK']],
            'PHONE' => [['VALUE' => $data['mobile'], 'VALUE_TYPE' => 'WORK']],
        ]
    ]);

    header('Location: listing_agents.php');
}
?>

<?php include 'includes/header.php'; ?>

<!-- Main Content -->
<div class="flex-grow-1" style="height: 100vh; overflow-y: auto;">
    <!-- Fixed Topbar -->
    <?php include 'includes/topbar.php'; ?>

    <!-- Listing Agents Content -->
    <div class="container px-3 px-md-5 py-2 py-md-4">
        <h2 class="display-10 fw-bold text-primary container">Listing Agents</h2>
        <div class="custom-card container mt-4">
            <!-- Add Location Button -->
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addLandlordModal">
                    <i class="fas fa-plus me-2"></i>Add Listing Agent
                </button>
            </div>

            <!-- Listing Agents Table -->
            <div class="table-responsive">
                <table class="table table-borderless text-center">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Designation</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="locationTableBody">
                        <?php foreach ($listing_agents as $listing_agent) : ?>
                            <tr>
                                <td><?= $listing_agent['id'] ?></td>
                                <td><?= $listing_agent['ufCrm46AgentName'] ?></td>
                                <td><?= $listing_agent['ufCrm46AgentEmail'] ?></td>
                                <td><?= $listing_agent['ufCrm46AgentMobile'] ?></td>
                                <td><?= $listing_agent['ufCrm46AgentDesignation'] ?></td>
                                <td><?= $listing_agent['ufCrm46AgentRole'] ?></td>
                                <td>
                                    <form action="./delete_agent.php" method="POST">
                                        <input type="hidden" name="agentId" value="<?= $listing_agent['id'] ?>">
                                        <input type="hidden" name="entityTypeId" value="<?= LISTING_AGENTS_ENTITY_TYPE_ID ?>">
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

    <?php include 'includes/modals/add_listing_agent.php'; ?>
</div>

<?php include 'includes/footer.php'; ?>