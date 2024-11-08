<?php
function addWatermark($sourceImagePath, $destinationImagePath)
{
    // Ensure the source image exists
    if (!file_exists($sourceImagePath)) {
        echo "Source image file does not exist: $sourceImagePath<br>";
        return false;
    }

    // Determine image type and load accordingly
    $imageType = exif_imagetype($sourceImagePath);
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($sourceImagePath);
            break;
        case IMAGETYPE_PNG:
            $image = imagecreatefrompng($sourceImagePath);
            break;
        default:
            echo "Unsupported image type: $imageType<br>";
            return false;
    }

    if (!$image) {
        echo "Failed to load source image. Check if the file is a valid image.<br>";
        return false;
    }

    // Load the watermark
    $watermark = imagecreatefrompng('./assets/watermark.png');
    if (!$watermark) {
        echo "Failed to load watermark image. Ensure the watermark file exists and is a valid PNG image.<br>";
        imagedestroy($image);
        return false;
    }

    // Get dimensions of the source image and watermark
    $imageWidth = imagesx($image);
    $imageHeight = imagesy($image);
    $watermarkWidth = imagesx($watermark);
    $watermarkHeight = imagesy($watermark);

    // Calculate position for the watermark to be centered
    $x = ($imageWidth - $watermarkWidth) / 2;
    $y = ($imageHeight - $watermarkHeight) / 2;

    // Merge the watermark onto the image
    if (!imagecopy($image, $watermark, $x, $y, 0, 0, $watermarkWidth, $watermarkHeight)) {
        echo "Failed to merge watermark onto the image.<br>";
        imagedestroy($image);
        imagedestroy($watermark);
        return false;
    }

    // Save the image with watermark
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $saved = imagejpeg($image, $destinationImagePath);
            break;
        case IMAGETYPE_PNG:
            $saved = imagepng($image, $destinationImagePath);
            break;
    }

    if (!$saved) {
        echo "Failed to save the image with watermark.<br>";
        imagedestroy($image);
        imagedestroy($watermark);
        return false;
    }

    // Free up memory
    imagedestroy($image);
    imagedestroy($watermark);

    return true;
}

function generateRandomColor()
{
    do {
        $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    } while (in_array($color, colors));
    $colors[] = $color;
    return $color;
}

function duplicateGroupCount($groups)
{
    $count = 0;
    foreach ($groups as $key => $group) {
        if (count($group) > 1) {
            $count++;
        }
    }
    return $count;
}

function getFilterConditions($filter = null, $otherFilters = [])
{
    $filterConditions = [];

    if (in_array($filter, ['DRAFT', 'LIVE', 'PENDING', 'ARCHIVED', 'DUPLICATE', 'WAITING_PUBLISH', 'HOT_PROPERTIES', 'PHOTO_REQUEST'])) {
        $filterConditions['ufCrm42Status'] = $filter;
    }

    $filterMapping = [
        'community' => '%ufCrm42Community',
        'subCommunity' => '%ufCrm42SubCommunity',
        'building' => '%ufCrm42Tower',
        'unitNo' => 'ufCrm42UnitNumber',
        'permit' => 'ufCrm42PermitNumber',
        'listingOwner' => 'ufCrm42OwnerName',
        'listingTitle' => 'ufCrm42TitleEn',
        // 'category' => 'ufCrm42Category',
        'propertyType' => 'ufCrm42PropertyType',
        // 'saleRent' => 'ufCrm42SaleRent',
        'listingAgent' => 'ufCrm42ListingAgent',
        'landlord' => 'ufCrm42OwnerName',
        'landlordEmail' => 'ufCrm42EmailAddress',
        'landlordPhone' => 'ufCrm42PhoneNumber',
        'bedrooms' => 'ufCrm42Bedroom',
        'developers' => 'ufCrm42Developers',
        'price' => 'ufCrm42Price',
        // 'portals' => 'ufCrm42Portals',
    ];

    foreach ($filterMapping as $key => $dbField) {
        if (!empty($otherFilters[$key])) {
            $filterConditions[$dbField] = $otherFilters[$key];
        }
    }

    return $filterConditions;
}
