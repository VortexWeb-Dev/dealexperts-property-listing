<?php
require_once(__DIR__ . '/crest/crest.php');
require_once(__DIR__ . '/crest/settings.php');
require 'vendor/autoload.php';

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
    $last_property_id = $last_property['id'] ?? 0;
}

$reference_number = $listing_reference . '-' . ($last_property_id + 1);

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$location_mapping = [
    'Marya Vista.xlsx' => [
        'city' => 'Abu Dhabi',
        'community' => 'Al Maryah Island',
        'sub_community' => 'Al Maryah Vista',
        'building' => ''
    ],
    'New Microsoft Excel Worksheet.xlsx' => [
        'city' => 'Abu Dhabi',
        'community' => 'Saadiyat Island',
        'sub_community' => 'Saadiyat Reserve',
        'building' => ''
    ],
    'The Magnolias.xlsx' => [
        'city' => 'Abu Dhabi',
        'community' => 'Yas Island',
        'sub_community' => 'Yas Acres',
        'building' => 'The Magnolias'
    ],
    'Waters Edge.xlsx' => [
        'city' => 'Abu Dhabi',
        'community' => 'Yas Island',
        'sub_community' => 'Waters Edge',
        'building' => '' // TODO: Add Waters Edge building
    ],
    'Saadiyat Beach Villas.xlsx' => [
        'city' => 'Abu Dhabi',
        'community' => 'Saadiyat Island',
        'sub_community' => 'Saadiyat Beach',
        'building' => 'Saadiyat Beach Villas'
    ],
    'Saadiyat Beach Residences.xlsx' => [
        'city' => 'Abu Dhabi',
        'community' => 'Saadiyat Island',
        'sub_community' => 'Saadiyat Beach',
        'building' => 'Saadiyat Beach Residences'
    ],
    'Noya Viva.xlsx' => [
        'city' => 'Abu Dhabi',
        'community' => 'Yas Island',
        'sub_community' => 'Noya',
        'building' => 'Noya Viva'
    ],
    'Mayan.xlsx' => [
        'city' => 'Abu Dhabi',
        'community' => 'Yas Island',
        'sub_community' => 'Mayan',
        'building' => '' // TODO: Add Mayan building
    ],
    'Noya Luma.xlsx' => [
        'city' => 'Abu Dhabi',
        'community' => 'Yas Island',
        'sub_community' => 'Noya',
        'building' => 'Noya Luma'
    ],
    'Al Reeman 1.xlsx' => [
        'city' => 'Abu Dhabi',
        'community' => 'Al Shamkha',
        'sub_community' => 'Al Reeman',
        'building' => 'Al Reeman 1'
    ],
    'Al Reeman2.xlsx' => [
        'city' => 'Abu Dhabi',
        'community' => 'Al Shamkha',
        'sub_community' => 'Al Reeman',
        'building' => 'Al Reeman 2'
    ],
    'Yas Acres.xlsx' => [
        'city' => 'Yas Island',
        'community' => 'Yas Acres',
        'sub_community' => '', // TODO: Add sub-community
        'building' => '' // TODO: Add building
    ],
    'Noya.xlsx' => [
        'city' => 'Abu Dhabi',
        'community' => 'Yas Island',
        'sub_community' => 'Noya',
        'building' => '' // TODO: Add building
    ],
];

if (isset($_FILES['xlsxFile']) && $_FILES['xlsxFile']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['xlsxFile']['tmp_name'];
    $fileName = $_FILES['xlsxFile']['name']; // Get the original file name
    $sheetName = $_POST['sheetName'] ?? null;

    try {
        $spreadsheet = IOFactory::load($fileTmpPath);
        $sheet = $sheetName ? $spreadsheet->getSheetByName($sheetName) : $spreadsheet->getActiveSheet();

        if (!$sheet) {
            echo "Sheet '$sheetName' not found.";
            exit;
        }

        // Define column references for specific fields
        if ($fileName == "Marya Vista.xlsx") {
            // Field mapping for "Marya Vista.xlsx" using column references
            $fieldMapping = [
                'A' => 'ufCrm42UnitNumber',       // A column contains unit number
                'B' => 'ufCrm42Community',        // B column contains location/community                                                                                                                                                                                                   
                'C' => 'ufCrm42SubCommunity',     // C column contains sub location
                'D' => 'ufCrm42PropertyType',     // D column contains property type
                'E' => 'ufCrm42Bedroom',          // E column contains bedroom number
                'G' => 'ufCrm42OwnerName',        // G column contains owner name
                'H' => 'ufCrm42PhoneNumber',      // H column contains owner phone number                                                                                                   
            ];
        } elseif ($fileName == "New Microsoft Excel Worksheet.xlsx" || $fileName == "The Magnolias.xlsx") {
            // Field mapping for "New Microsoft Excel Worksheet.xlsx" and "The Magnolias.xlsx" using column references
            $fieldMapping = [
                'A' => 'ufCrm42UnitNumber',        // A column contains unit number
                'B' => 'ufCrm42OwnerName',         // B column contains owner name
                'C' => 'ufCrm42PhoneNumber',       // C column contains owner phone number
            ];
        } else {
            // Default field mapping for regular files using column references
            $fieldMapping = [
                'A' => 'ufCrm42Community',        // A column contains community
                'B' => 'ufCrm42Precinct',         // B column contains precinct
                'C' => 'ufCrm42UnitNumber',       // C column contains unit number
                'D' => 'ufCrm42OwnerName',        // D column contains owner name
                'E' => 'ufCrm42EmailAddress',     // E column contains email address
                'F' => 'ufCrm42IsdCode',          // F column contains ISD code
                'G' => 'ufCrm42PhoneNumber',      // G column contains phone number
            ];
        }

        $locationData = $location_mapping[$fileName] ?? null;

        if ($locationData) {
            $rowData['ufCrm42City'] = $locationData['city'] ?? '';
            $rowData['ufCrm42Community'] = $locationData['community'] ?? '';
            $rowData['ufCrm42SubCommunity'] = $locationData['sub_community'] ?? '';
            $rowData['ufCrm42Tower'] = $locationData['building'] ?? '';
        }

        // Iterate over rows, skipping the first row (assuming it's the header)
        foreach ($sheet->getRowIterator(2) as $row) { // Start at row 2 to skip headers
            $rowData = [];

            // Loop over columns
            foreach ($fieldMapping as $column => $fieldName) {
                $cellValue = $sheet->getCell($column . $row->getRowIndex())->getValue();
                $rowData[$fieldName] = $cellValue;
            }

            // Add location fields from the mapping
            $locationData = $location_mapping[$fileName] ?? null;

            if ($locationData) {
                $rowData['ufCrm42City'] = $locationData['city'] ?? '';
                $rowData['ufCrm42Community'] = $locationData['community'] ?? '';
                $rowData['ufCrm42SubCommunity'] = $locationData['sub_community'] ?? '';
                $rowData['ufCrm42Tower'] = $locationData['building'] ?? '';
            }

            // Add fixed fields
            $rowData['title'] = $rowData['ufCrm42Community'] . ' - ' . $rowData['ufCrm42UnitNumber'];
            $rowData['ufCrm42AgentName'] = 'Laith Maisara Abu Omar';
            $rowData['ufCrm42AgentEmail'] = 'laith@dere.ae';
            $rowData['ufCrm42AgentPhone'] = '971545366166';
            $rowData['ufCrm42ReferenceNumber'] = $reference_number;

            // Call Bitrix24 CRM to add item
            CRest::call('crm.item.add', ['entityTypeId' => INTERNAL_LISTING_ENTITY_TYPE_ID, 'fields' => $rowData]);

            $response = CRest::call('crm.item.add', [
                'entityTypeId' => LANDLORDS_ENTITY_TYPE_ID,
                'fields' => [
                    'ufCrm50LandlordName' => $rowData['ufCrm42OwnerName'],
                    'ufCrm50LandlordEmail' => $rowData['ufCrm42EmailAddress'],
                    'ufCrm50LandlordMobile' => $rowData['ufCrm42PhoneNumber'],
                ]
            ]);

            $contact_res = CRest::call('crm.contact.add', [
                'fields' => [
                    'NAME' => $rowData['ufCrm42OwnerName'],
                    'TYPE_ID' => 'UC_1KUBSF',
                    'EMAIL' => [['VALUE' => $rowData['ufCrm42EmailAddress'], 'VALUE_TYPE' => 'WORK']],
                    'PHONE' => [['VALUE' => $rowData['ufCrm42PhoneNumber'], 'VALUE_TYPE' => 'WORK']],
                ]
            ]);
        }

        header('Location: index.php?success=1');
        exit;
    } catch (Exception $e) {
        echo "Error processing the file: " . $e->getMessage();
        exit;
    }
} else {
    header('Location: index.php?error=1');
    exit;
}
