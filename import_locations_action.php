<?php
require_once(__DIR__ . '/crest/crest.php');
require_once(__DIR__ . '/crest/settings.php');


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
        if ($line != '') {
            $data = str_getcsv($line, $separator);
            if ($isHeader) {
                $header = $data;
                $isHeader = false;
            } else {
                $dataRows[] = $data;
            }
        }
    }

    foreach ($dataRows as $row) {
        $fullLocation = trim(implode(' - ', array_filter($row)));
        $fields = [
            'ufCrm48City' => $row[0] ?? '',
            'ufCrm48Community' => $row[1] ?? '',
            'ufCrm48SubCommunity' => $row[2] ?? '',
            'ufCrm48Building' => $row[3] ?? '',
            'ufCrm48Location' => $fullLocation,
        ];

        CRest::call(
            'crm.item.add',
            [
                'entityTypeId' => LOCATIONS_ENTITY_TYPE_ID,
                'fields' => $fields,
            ]
        );

        if ($row[0] && $row[0] != '') {
            CRest::call('crm.item.add', [
                'entityTypeId' => CITIES_ENTITY_TYPE_ID,
                'fields' => [
                    'ufCrm56City' => $row[0],
                ]
            ]);
        }

        if ($row[1] && $row[1] != '') {
            CRest::call('crm.item.add', [
                'entityTypeId' => COMMUNITIES_ENTITY_TYPE_ID,
                'fields' => [
                    'ufCrm58Community' => $row[1],
                ]
            ]);
        }

        if ($row[2] && $row[2] != '') {
            CRest::call('crm.item.add', [
                'entityTypeId' => SUB_COMMUNITIES_ENTITY_TYPE_ID,
                'fields' => [
                    'ufCrm60SubCommunity' => $row[2],
                ]
            ]);
        }

        if ($row[3] && $row[3] != '') {
            CRest::call('crm.item.add', [
                'entityTypeId' => BUILDINGS_ENTITY_TYPE_ID,
                'fields' => [
                    'ufCrm62Building' => $row[3],
                ]
            ]);
        }
    }

    header('Location: locations.php?success=1');
    exit;
} else {
    header('Location: locations.php?error=1');
    exit;
}
