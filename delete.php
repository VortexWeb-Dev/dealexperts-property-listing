<?php
require_once(__DIR__ . '/crest/crest.php');
require_once(__DIR__ . '/crest/settings.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and decode the JSON-encoded array
    $propertyIds = isset($_POST['property_ids']) ? json_decode($_POST['property_ids'], true) : [];

    if (!empty($propertyIds)) {

        foreach ($propertyIds as $propertyId) {
            $response = CRest::call('crm.item.delete', [
                'entityTypeId' => PROPERTY_LISTING_ENTITY_TYPE_ID,
                'id' => $propertyId
            ]);

            echo 'Property with ID ' . $propertyId . ' deleted successfully.' . PHP_EOL;
        }
    } else {
        echo 'No properties selected for deletion.';
    }
} else {
    echo 'Invalid request method.';
}
