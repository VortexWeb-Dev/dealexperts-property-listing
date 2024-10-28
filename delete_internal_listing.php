<?php
require_once __DIR__ . '/crest/crest.php';
require_once __DIR__ . '/crest/settings.php';

$data = $_POST;

if (isset($data['id'])) {
    $response = CRest::call('crm.item.delete', [
        'entityTypeId' => $data['entityTypeId'],
        'id' => $data['id']
    ]);

    if ($data['entityTypeId'] == INTERNAL_LISTING_ENTITY_TYPE_ID) {
        header('Location: index.php');
    }
}
