<?php
require_once __DIR__ . '/crest/crest.php';
require_once __DIR__ . '/crest/settings.php';

$response = CRest::call('crm.item.get', [
    'entityTypeId' => PROPERTY_LISTING_ENTITY_TYPE_ID,
    'id' => $_GET['id']
]);

$property = $response['result']['item'] ?? null;
?>
<?php include 'includes/header.php'; ?>

<!-- Main Content Area -->
<div class="flex-grow-1" style="height: 100vh; overflow-y: auto;">
    <!-- Fixed Topbar -->
    <?php include 'includes/topbar.php'; ?>

    <div class="container px-3 px-md-5 py-2 py-md-4">
        <h2 class="display-10 fw-bold text-primary">Property Details</h2>
        <div class="custom-card">
            <div class="row mb-3">
                <div class="col-md-8">
                    <h1 class="h3"><?php echo $property['title'] ?></h1>
                </div>
                <div class="col-md-4 text-md-end">
                    <h2 class="h3">AED <?php echo $property['ufCrm42Price'] ?></h2>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <p class="text-muted">
                        <i class="fas fa-map-marker-alt"></i> <?php echo $property['ufCrm42City'] . ' - ' . $property['ufCrm42Community'] . ' - ' . $property['ufCrm42SubCommunity'] . ' - ' . $property['ufCrm42Tower'] ?>
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3 d-flex flex-wrap gap-1">
                        <span class="badge bg-primary property-tag"><?php echo $property['ufCrm42PropertyType'] == 'AP' ? 'Apartment' : $property['ufCrm42PropertyType'] ?></span>
                        <span class="badge bg-secondary property-tag">Beds: <?php echo $property['ufCrm42Bedroom'] ?></span>
                        <span class="badge bg-secondary property-tag">Baths: <?php echo $property['ufCrm42Bathroom'] ?></span>
                        <span class="badge bg-secondary property-tag">Sq Ft: <?php echo $property['ufCrm42Size'] ?></span>
                    </div>

                    <h2>Description</h2>
                    <?php echo $property['ufCrm42DescriptionEn'] ?>

                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header d-flex flex-column">
                            <h5 class="mb-0">Listed By</h5>
                            <p class="text-small text-muted"><?php echo $property['ufCrm42OwnerName'][0] ?></p>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Property Details</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td>Unit Number:</td>
                                    <td><?php echo isset($property['ufCrm42UnitNumber']) ? $property['ufCrm42UnitNumber'] : 'Unavailable'; ?></td>
                                </tr>

                                <tr>
                                    <td>Price:</td>
                                    <td>AED <?php echo $property['ufCrm42Price'] ?></td>
                                </tr>
                                <tr>
                                    <td>Bedrooms:</td>
                                    <td><?php echo $property['ufCrm42Bedroom'] ?></td>
                                </tr>
                                <tr>
                                    <td>Bathrooms:</td>
                                    <td><?php echo $property['ufCrm42Bathroom'] ?></td>
                                </tr>
                                <tr>
                                    <td>Parking:</td>
                                    <td><?php echo $property['ufCrm42Parking'] ?></td>
                                </tr>
                                <tr>
                                    <td>Property Size:</td>
                                    <td><?php echo $property['ufCrm42Size'] ?></td>
                                </tr>
                                <tr>
                                    <td>Property Type:</td>
                                    <td><?php echo $property['ufCrm42PropertyType'] ?></td>
                                </tr>
                                <tr>
                                    <td>Offering Type:</td>
                                    <td><?php echo $property['ufCrm42OfferingType'] ?></td>
                                </tr>
                                <tr>
                                    <td>Property Status:</td>
                                    <td><?php echo $property['ufCrm42Status'] ?></td>
                                </tr>
                            </table>
                            <!-- Additional details -->
                            <!-- <h5 class="card-title mt-4">Additional details</h5>
                                    <table class="table table-sm">
                                        <tr>
                                            <td>Financial Status:</td>
                                            <td><?php echo $property['ufCrm42FinancialStatus'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Available From:</td>
                                            <td><?php echo $property['ufCrm42AvailableFrom'] ?></td>
                                        </tr>
                                    </table> -->
                        </div>
                    </div>
                </div>

            </div>


            <!-- Image gallery -->
            <!-- <div class="row mt-3 w-100 listing-images">
                        <?php for ($i = 0; $i < count($property['ufCrm42PhotoLinks']); $i++) { ?>
                            <div class="col-6 col-md-3 mb-3">
                                <img src="<?= htmlspecialchars($property['ufCrm42PhotoLinks'][$i]) ?>" alt="Image <?= $i + 1 ?>" class="img-fluid rounded">
                            </div>
                        <?php } ?>
                    </div> -->

            <!-- Image Preview -->
            <div class="gallery-preview mb-4">
                <img src="<?= htmlspecialchars($property['ufCrm42PhotoLinks'][0]) ?>" alt="Preview" id="previewImage">
            </div>

            <!-- Thumbnails Carousel -->
            <div class="carousel-container">
                <div class="carousel">
                    <!-- Carousel items will be dynamically added here -->
                    <?php for ($i = 0; $i < count($property['ufCrm42PhotoLinks']); $i++) { ?>
                        <div class="carousel-item">
                            <img src="<?= htmlspecialchars($property['ufCrm42PhotoLinks'][$i]) ?>" alt="image - .<?= $i + 1 ?>" data-src="<?= htmlspecialchars($property['ufCrmPhotoLinks'][$i]) ?>" class="thumbnail">
                        </div>
                    <?php } ?>
                </div>
                <button class="prev-button">&#10094;</button>
                <button class="next-button">&#10095;</button>
            </div>


            <!-- Private Aminities -->
            <!-- <section class="section-bg">
                        <div class="row">
                            <div class="col-12">
                                <h2 class="mb-4">Private Amenities</h2>
                                <?php if (!empty($property['ufCrm42Amenities']) && is_array($property['ufCrm42Amenities'])): ?>
                                    <div>
                                        <?php foreach (explode(',', $property['ufCrm42Amenities'][0]) as $amenity): ?>
                                            <span class="amenity-badge"><?php echo htmlspecialchars($amenity); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p>No amenities available.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </section> -->

            <!-- Floor plans -->
            <!-- <section class="section-bg">
                        <div class="row">
                            <div class="col-12">
                                <h2 class="mb-4">Floor plans</h2>

                                <div class="bg-light p-5 text-center">
                                    <a href="<?= htmlspecialchars($property['ufCrm42FloorPlan'][0]['urlMachine']) ?>">Download floor plan</a>
                                </div>
                            </div>
                        </div>
                    </section> -->
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>