<?php
require_once __DIR__ . '/crest/crest.php';
require_once __DIR__ . '/crest/settings.php';
require_once __DIR__ . '/utils/index.php';

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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $property = $_POST;

    // echo '<pre>';
    // print_r($property);
    // echo '</pre>';

    $photo = $_FILES['photo'];
    $floorPlan = $_FILES['floorPlan'];

    $parentDir = './tmp';

    if (!is_dir($parentDir)) {
        if (!mkdir($parentDir, 0777, true)) {
            echo "Failed to create temporary directory: $parentDir<br>";
            exit();
        }
    }

    $photoTmpPath = $parentDir . '/' . basename($photo['name']);
    $floorPlanTmpPath = $parentDir . '/' . basename($floorPlan['name']);

    if ($photo['error'] !== UPLOAD_ERR_OK) {
        echo "Photo upload error: " . $photo['error'] . "<br>";
    }

    if ($floorPlan['error'] !== UPLOAD_ERR_OK) {
        echo "Floor plan upload error: " . $floorPlan['error'] . "<br>";
    }

    if (!move_uploaded_file($photo['tmp_name'], $photoTmpPath)) {
        echo "Failed to move photo to tmp directory.<br>";
    }

    if (!move_uploaded_file($floorPlan['tmp_name'], $floorPlanTmpPath)) {
        echo "Failed to move floor plan to tmp directory.<br>";
    }

    if (!file_exists($photoTmpPath)) {
        echo "Source image file does not exist: $photoTmpPath<br>";
    }

    if (!file_exists($floorPlanTmpPath)) {
        echo "Source image file does not exist: $floorPlanTmpPath<br>";
    }

    if (
        file_exists($photoTmpPath) && file_exists($floorPlanTmpPath) &&
        addWatermark($photoTmpPath, $photoTmpPath) &&
        addWatermark($floorPlanTmpPath, $floorPlanTmpPath)
    ) {
        $offer_type = '';

        if ($property['offer_type'] == 'rent' && $property['property_type'] == 'residential') {
            $offer_type = "RR";
        } else if ($property['offer_type'] == 'rent' && $property['property_type'] == 'commercial') {
            $offer_type = "CR";
        } else if ($property['offer_type'] == 'sale' && $property['property_type'] == 'residential') {
            $offer_type = "RS";
        } else if ($property['offer_type'] == 'sale' && $property['property_type'] == 'commercial') {
            $offer_type = "CS";
        }

        $fields = [
            'TITLE' => $property['titleDeed'],
            'ufCrm42ReferenceNumber' => $reference_number,
            'ufCrm42PermitNumber' => $property['permitNumber'],
            'ufCrm42Status' => "LIVE",
            'ufCrm42PropertyType' => $property['propertyType'],
            'ufCrm42OfferingType' => $offer_type,
            'ufCrm42Size' => $property['size'],
            'ufCrm42UnitNumber' => $property['unitNo'],
            'ufCrm42Furnished' => $property['furnished'],
            'ufCrm42Bedroom' => $property['bedrooms'],
            'ufCrm42Bathroom' => $property['bathrooms'],
            'ufCrm42Parking' => $property['parkingSpaces'],
            'ufCrm42TotalPlotSize' => $property['totalPlotSize'],
            'ufCrm42LotSize' => $property['lotSize'],
            'ufCrm42BuildupArea' => $property['buildUpArea'],
            'ufCrm42LayoutType' => $property['layoutType'],
            'ufCrm42ProjectName' => $property['projectName'],
            'ufCrm42ProjectStatus' => $property['projectStatus'],
            'ufCrm42Ownership' => $property['ownership'],
            'ufCrm42Developers' => $property['developers'],
            'ufCrm42BuildYear' => $property['buildYear'],
            'ufCrm42Amenities' => $property['amenities'],

            'ufCrm42AgentName' => $property['listingAgent'],
        
            'ufCrm42ListingOwner' => $property['listingOwner'],
            'ufCrm42OwnerName' => [$property['landlordName']],
            'ufCrm42EmailAddress' => $property['landlordEmail'],
            'ufCrm42PhoneNumber' => $property['landlordContact'],
            'ufCrm42Availability' => $property['availability'],
            'ufCrm42AvailableFrom' => $property['availableFrom'],

            'ufCrm42ReraPermitNumber' => $property['reraPermitNumber'],
            'ufCrm42ReraPermitIssueDate' => $property['reraPermitIssueDate'],
            'ufCrm42ReraPermitExpirationDate' => $property['reraPermitExpirationDate'],
            'ufCrm42DtcmPermitNumber' => $property['dtcmPermitNumber'],

            'ufCrm42TitleEn' => $property['title_english'],
            'ufCrm42DescriptionEn' => $property['description_english'],
            'ufCrm42TitleAr' => $property['title_arabic'],
            'ufCrm42DescriptionAr' => $property['description_arabic'],

            'ufCrm42Price' => $property['price'],
            'ufCrm42HidePrice' => $property['hidePrice'],
            'ufCrm42PaymentMethod' => $property['paymentMethod'],
            'ufCrm42DownPaymentPrice' => $property['downPayment'],
            'ufCrm42NoOfCheques' => $property['numCheques'],
            'ufCrm42ServiceCharge' => $property['serviceCharge'],
            'ufCrm42FinancialStatus' => $property['financialStatus'],
            'ufCrm42YearlyPrice' => $property['yearlyPrice'],
            'ufCrm42MonthlyPrice' => $property['monthlyPrice'],
            'ufCrm42WeeklyPrice' => $property['weeklyPrice'],
            'ufCrm42DailyPrice' => $property['dailyPrice'],

            'ufCrm42PhotoLinks' => [
                $photoTmpPath
            ],
            'ufCrm42VideoTourUrl' => $property['videoUrl'],
            'ufCrm_83_360_VIEW_URL' => $property['viewUrl'],
            'ufCrm42QrCodePropertyBooster' => $property['qrCode'],

            'ufCrm42Location' => $property['propertyLocation'],
            'ufCrm42City' => $property['propertyCity'],
            'ufCrm42Community' => $property['propertyCommunity'],
            'ufCrm42SubCommunity' => $property['propertySubCommunity'],
            'ufCrm42Tower' => $property['propertyTower'],
            'ufCrm42BayutLocation' => $property['bayutLocation'],
            'ufCrm42BayutCity' => $property['bayutCity'],
            'ufCrm42BayutCommunity' => $property['bayutCommunity'],
            'ufCrm42BayutSubCommunity' => $property['bayutSubCommunity'],
            'ufCrm42BayutTower' => $property['bayutTower'],
            'ufCrm42Latitude' => $property['latitude'],
            'ufCrm42Longitude' => $property['longitude'],
            'ufCrm42Geopoints' => $property['latitude'] . ',' . $property['longitude'],
            'ufCrm42FloorPlan' => [
                0 => $floorPlanTmpPath
            ],

            'ufCrm42PfEnable' => $property['pfEnable'] == 'on' ? 'Y' : 'N',
            'ufCrm42BayutEnable' => $property['bayutEnable'] == 'on' ? 'Y' : 'N',
            'ufCrm42DubizleEnable' => $property['dubizleEnable'] == 'on' ? 'Y' : 'N',
            'ufCrm42WebsiteEnable' => $property['websiteEnable'] == 'on' ? 'Y' : 'N',
        ];

        // Upload to Bitrix24
        $response = CRest::call('crm.item.add', [
            'entityTypeId' => PROPERTY_LISTING_ENTITY_TYPE_ID,
            'fields' => $fields
        ]);

        header("Location: index.php");
    } else {
        echo "Failed to add watermark to images.<br>";
    }
}
