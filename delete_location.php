<?php
require_once __DIR__ . '/crest/crest.php';
require_once __DIR__ . '/crest/settings.php';

$data = $_POST;

if (isset($data['locationId'])) {
    $response = CRest::call('crm.item.delete', [
        'entityTypeId' => $data['entityTypeId'],
        'id' => $data['locationId']
    ]);

    if ($data['entityTypeId'] == LOCATIONS_ENTITY_TYPE_ID) {
        header('Location: locations.php');
    } else {
        header('Location: bayut_locations.php');
    }
}
