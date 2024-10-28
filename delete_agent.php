<?php
require_once __DIR__ . '/crest/crest.php';
require_once __DIR__ . '/crest/settings.php';

$data = $_POST;

if (isset($data['agentId'])) {
    $response = CRest::call('crm.item.delete', [
        'entityTypeId' => $data['entityTypeId'],
        'id' => $data['agentId']
    ]);

    if ($data['entityTypeId'] == LISTING_AGENTS_ENTITY_TYPE_ID) {
        header('Location: listing_agents.php');
    }
}
