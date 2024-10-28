<?php
require_once __DIR__ . '/crest/crest.php';
require_once __DIR__ . '/crest/settings.php';

$data = $_POST;

if (isset($data['landlordId'])) {
    $response = CRest::call('crm.item.delete', [
        'entityTypeId' => $data['entityTypeId'],
        'id' => $data['landlordId']
    ]);

    if ($data['entityTypeId'] == LANDLORDS_ENTITY_TYPE_ID) {
        header('Location: landlords.php');
    }
}
