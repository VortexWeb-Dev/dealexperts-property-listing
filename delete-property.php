<?php
require_once __DIR__ . '/./crest/crest.php';
require_once __DIR__ . '/./crest/settings.php';

$property_id = $_GET['id'];


if ($property_id) {
    $response = CRest::call('crm.item.delete', [
        'entityTypeId' => PROPERTY_LISTING_ENTITY_TYPE_ID,
        'id' => $property_id
    ]);

    header('Location: index.php');
}
