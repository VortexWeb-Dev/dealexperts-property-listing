<?php
require_once __DIR__ . '/crest/crest.php';
require_once __DIR__ . '/crest/settings.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle file upload
    if (isset($_FILES['watermarkImage']) && $_FILES['watermarkImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/uploads/';

        // Create uploads directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique file name based on timestamp
        $fileName = 'watermark_' . time() . '.' . pathinfo($_FILES['watermarkImage']['name'], PATHINFO_EXTENSION);
        $uploadFile = $uploadDir . $fileName;

        // Allowed file types
        $allowedTypes = ['image/png', 'image/jpeg', 'image/gif'];
        $fileType = mime_content_type($_FILES['watermarkImage']['tmp_name']);

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['watermarkImage']['tmp_name'], $uploadFile)) {
                // Store the relative path of the uploaded file
                $watermarkImagePath = 'uploads/' . $fileName;
                echo "File uploaded successfully.";
            } else {
                echo "Error moving the uploaded file.";
            }
        } else {
            echo "Invalid file type. Please upload a valid image (PNG, JPEG, GIF).";
        }
    }

    $data = $_POST;

    // Prepare fields for the CRM update
    $fields = [
        'ufCrm52ListingReference' => $data['listingReference'],
        'ufCrm52Website' => $data['website'],
        'ufCrm52CompanyName' => $data['companyName'],
        'ufCrm52RentOrSale' => $data['rentOrSale'] == 'Rent' ? 45268 : 45270,
        'ufCrm52Watermark' => isset($data['watermark']) ? 'Y' : 'N',
        'ufCrm52AgentIdInReference' => isset($data['agentIdInReference']) ? 'Y' : 'N',
        'ufCrm52AgentCanEditLiveListings' => isset($data['agentCanEditLiveListing']) ? 'Y' : 'N',
        'ufCrm52AgentCanEditPendingListings' => isset($data['agentCanEditPendingListing']) ? 'Y' : 'N',
        'ufCrm52EmailNotification' => isset($data['emailNotification']) ? 'Y' : 'N',
        'ufCrm52WatermarkImage' => isset($watermarkImagePath) ? $watermarkImagePath : $settings['ufCrm52WatermarkImage'] // Use new or existing image
    ];

    // Update CRM with the new fields
    CRest::call('crm.item.update', [
        'entityTypeId' => GENERAL_SETTINGS_ENTITY_TYPE_ID,
        'id' => 2,
        'fields' => $fields
    ]);

    // Redirect to avoid form resubmission
    header('Location: settings.php');
    exit;
}

// Fetch existing settings
$result = CRest::call('crm.item.get', [
    'entityTypeId' => GENERAL_SETTINGS_ENTITY_TYPE_ID,
    'id' => 2
]);
$settings = $result['result']['item'] ?? [];
?>

<?php include 'includes/header.php'; ?>

<!-- Main Content -->
<div class="flex-grow-1" style="height: 100vh; overflow-y: auto;">
    <!-- Fixed Topbar -->
    <?php include 'includes/topbar.php'; ?>

    <!-- Settings Content -->
    <div class="container px-3 px-md-5 py-2 py-md-4">
        <div class="main-content">
            <form method="POST" enctype="multipart/form-data" action="./settings.php">
                <div class="row">
                    <div class="col-md-6 d-flex justify-content-center align-items-center">
                        <div class="card shadow watermark-card mb-4">
                            <div class="card-body d-flex flex-column align-items-center gap-3">
                                <h5 class="card-title mb-3">Edit Watermark Image</h5>
                                <!-- Property Image with Watermark Overlay -->
                                <div class="property-wrapper">
                                    <img src="./assets/property.jpg?<?php echo time(); ?>" alt="Property" class="property-image img-fluid rounded">
                                    <img src="<?= $settings['ufCrm52WatermarkImage'] ?>" alt="Watermark" class="watermark-overlay">
                                </div>
                                <div class="file-upload-wrapper w-100">
                                    <div class="file-upload">
                                        <input type="file" name="watermarkImage" class="form-control-file">
                                        <label>Upload Watermark</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- General Settings Form -->
                        <div class="card watermark-card">
                            <div class="card-body">
                                <h5 class="card-title mb-2 fw-light">General Settings</h5>
                                <div class="mb-3">
                                    <label for="listingReference" class="form-label fw-light">Listing Reference</label>
                                    <input type="text" class="form-control" id="listingReference" name="listingReference" value="<?= $settings['ufCrm52ListingReference'] ?? '' ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="website" class="form-label fw-light">Website</label>
                                    <input type="text" class="form-control" id="website" name="website" value="<?= $settings['ufCrm52Website'] ?? '' ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="companyName" class="form-label fw-light">Company Name</label>
                                    <input type="text" class="form-control" id="companyName" name="companyName" value="<?= $settings['ufCrm52CompanyName'] ?? '' ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-light">Rent or Sale</label>
                                    <select class="form-select mb-1" name="rentOrSale">
                                        <option selected><?= $settings['ufCrm52RentOrSale'] == 45268 ? 'Rent' : 'Sale' ?></option>
                                        <option value="45268">Rent</option>
                                        <option value="45270">Sale</option>
                                    </select>
                                </div>

                                <!-- Switches -->
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="watermark" id="watermark" <?= $settings['ufCrm52Watermark'] == 'Y' ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-light" for="watermark">Watermark</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="agentIdInReference" id="agentIdInReference" <?= $settings['ufCrm52AgentIdInReference'] == 'Y' ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-light" for="agentIdInReference">Agent ID in Reference</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="agentCanEditLiveListing" id="agentCanEditLiveListing" <?= $settings['ufCrm52AgentCanEditLiveListings'] == 'Y' ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-light" for="agentCanEditLiveListing">Agent Can Edit Live Listings</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="agentCanEditPendingListing" id="agentCanEditPendingListings" <?= $settings['ufCrm52AgentCanEditPendingListings'] == 'Y' ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-light" for="agentCanEditPendingListings">Agent Can Edit Pending Listings</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="emailNotification" id="emailNotification" <?= $settings['ufCrm52EmailNotification'] == 'Y' ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-light" for="emailNotification">Email Notification</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="d-flex justify-content-end align-items-center gap-2 mt-2">
                    <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                    <a href="./dashboard.php" class="btn btn-secondary btn-sm">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>