<?php
require_once __DIR__ . '/crest/crest.php';
require_once __DIR__ . '/crest/settings.php';

$data = $_POST;

if (isset($data['developerId'])) {
    $response = CRest::call('crm.item.delete', [
        'entityTypeId' => $data['entityTypeId'],
        'id' => $data['developerId']
    ]);

    if ($data['entityTypeId'] == DEVELOPERS_ENTITY_TYPE_ID) {
        header('Location: developers.php');
    }
}
