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

    // echo "<pre>";
    // print_r($dataRows);
    // echo "</pre>";

    foreach ($dataRows as $row) {
        $fullLocation = trim(implode(' - ', array_filter($row)));
        $fields = [
            'ufCrm48City' => $row[0] ?? '',
            'ufCrm48Community' => $row[1] ?? '',
            'ufCrm48SubCommunity' => $row[2] ?? '',
            'ufCrm48Building' => $row[3] ?? '',
            'ufCrm48Location' => $fullLocation,
        ];

        $result = CRest::call(
            'crm.item.list',
            [
                'entityTypeId' => LOCATIONS_ENTITY_TYPE_ID,
                'filter' => $fields,
            ]
        );

        // avoid inserting duplicate values
        if ($result['total'] == 0) {
            CRest::call('crm.item.add', ['entityTypeId' => LOCATIONS_ENTITY_TYPE_ID, 'fields' => $fields]);
        }
    }

    header('Location: locations.php?success=1');
    exit;
} else {
    header('Location: locations.php?error=1');
    exit;
}
