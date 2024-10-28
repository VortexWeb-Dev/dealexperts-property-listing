<?php
require_once(__DIR__ . '/crest/crest.php');
require_once(__DIR__ . '/crest/settings.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $propertyIdsString = isset($_POST['transferOwnerPropertyIds']) ? $_POST['transferOwnerPropertyIds'] : '';
    $owner_id = isset($_POST['owner_id']) ? $_POST['owner_id'] : '';

    $propertyIds = explode(',', $propertyIdsString);

    if (!empty($propertyIds)) {
        $owner_res = CRest::call('crm.item.get', [
            'entityTypeId' => LANDLORDS_ENTITY_TYPE_ID,
            'id' => $owner_id
        ]);
        $owner = $owner_res['result']['item'] ?? null;

        if (!$owner) {
            header('Location: index.php');
            exit;
        }

        foreach ($propertyIds as $propertyId) {
            $res = CRest::call('crm.item.update', [
                'entityTypeId' => PROPERTY_LISTING_ENTITY_TYPE_ID,
                'id' => $propertyId,
                'fields' => [
                    'ufCrm42ListingOwner' => $owner['ufCrm50LandlordName'],
                    'ufCrm42LandlordName' => $ownerEmail['ufCrm50LandlordName'],
                    'ufCrm42LandlordEmail' => $ownerPhone['ufCrm50LandlordEmail'],
                    'ufCrm42LandlordContact' => $ownerPhone['ufCrm50LandlordMobile'],
                ]
            ]);
        }

        header('Location: index.php');
    } else {
        header('Location: index.php');
    }
} else {
    echo 'Invalid request method.';
}
