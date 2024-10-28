<?php
require_once(__DIR__ . '/crest/crest.php');
require_once(__DIR__ . '/crest/settings.php');

// Get last property to get ID
$res = CRest::call('crm.item.list', [
    'entityTypeId' => PROPERTY_LISTING_ENTITY_TYPE_ID,
    'order' => [
        'id'  => 'DESC'
    ]
]);
$settings_res = CRest::call('crm.item.get', [
    'entityTypeId' => GENERAL_SETTINGS_ENTITY_TYPE_ID,
    'id' => 2
]);

$last_property = $res['result']['items'][0] ?? null;
$settings = $settings_res['result']['item'] ?? null;

$listing_reference  = $settings['ufCrm52ListingReference'] ?? 'DERE';

if ($last_property) {
    $last_property_id = $last_property['id'];
}

$reference_number = $listing_reference . '-' . ($last_property_id + 1);

if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['csvFile']['tmp_name'];
    $separator = $_POST['separator'];

    $fileContent = file($fileTmpPath);
    if ($fileContent === false) {
        echo "Error reading the file.";
        exit;
    }

    $header = [];
    $dataRows = [];
    $isHeader = true;

    foreach ($fileContent as $line) {
        $data = str_getcsv($line, $separator);
        if ($isHeader) {
            $header = $data;
            $isHeader = false;
        } else {
            $dataRows[] = $data;
        }
    }

    foreach ($dataRows as $row) {
        $fields = [
            'title' => $row[0] . ' - ' . $row[2],
            'ufCrm42Community' => $row[0],
            'ufCrm42Precinct' => $row[1],
            'ufCrm42UnitNumber' => $row[2],
            'ufCrm42OwnerName' => [$row[3]],
            'ufCrm42EmailAddress' => $row[4],
            'ufCrm42IsdCode' => $row[5],
            'ufCrm42PhoneNumber' => $row[6],
            'ufCrm42AgentName' => 'Laith Maisara Abu Omar',
            'ufCrm42AgentEmail' => 'laith@dere.ae',
            'ufCrm42AgentPhone' => '971545366166',
            'ufCrm42ReferenceNumber' => $reference_number
        ];

        CRest::call('crm.item.add', ['entityTypeId' => INTERNAL_LISTING_ENTITY_TYPE_ID, 'fields' => $fields]);
    }

    header('Location: index.php?success=1');
    exit;
} else {
    header('Location: index.php?error=1');
    exit;
}
