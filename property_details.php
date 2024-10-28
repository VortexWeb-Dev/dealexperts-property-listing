<?php
$developers = [
	['name' => 'Developer 1', 'value' => 'developer_1'],
	['name' => 'Developer 2', 'value' => 'developer_2'],
	['name' => 'Developer 3', 'value' => 'developer_3'],
];

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Property Listing</title>
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<!-- Chart.js -->
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
	<!-- Custom CSS -->
	<style>
		.settings-menu,
		.bulk-options-menu {
			display: none;
		}

		.dropdown-menu.show {
			display: block;
		}

		.nav-pills .nav-link {
			border-radius: 50px;
		}

		.nav-pills .nav-link.completed {
			background-color: #28a745;
			/* Green */
			color: white;
		}

		.nav-pills .nav-link.active {
			background-color: #007bff;
			/* Blue */
			color: white;
		}

		.nav-pills .nav-link:not(.active):not(.completed) {
			background-color: #e9ecef;
			/* Light gray */
		}

		/* Hide the radio buttons */
		input[type="radio"] {
			display: none;
		}

		/* Label styling for unselected state */
		label {
			cursor: pointer;
			border: 2px solid transparent;
			padding: 10px;
			border-radius: 10px;
		}

		/* Styling for selected radio buttons */
		input[type="radio"]:checked+img {
			padding: 5px;
			border: 2px solid blue;
			border-radius: 10px;
		}

		/* Additional styling for text under images */
		label img {
			display: block;
			margin: 0 auto;
		}
	</style>
</head>

<body>
	<div class="container mt-4">



		<!-- Top Box -->
		<div class="d-flex justify-content-between align-items-center mb-4 p-3 border rounded bg-light">
			<h2 class="mb-0">Property Listing</h2>

			<div class="d-flex align-items-center">

				<button type="button" class="btn btn-outline-success position-relative mx-4">
					Availability <i class="fa-regular fa-clipboard"></i>
					<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
						322
					</span>
				</button>
				<button type="button" class="btn btn-outline-primary position-relative">
					Listings <i class="fa-solid fa-list"></i>
					<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
						1079
					</span>
				</button>

			</div>

			<div class="position-relative">
				<div class="dropdown">
					<button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
						<i class="fa-solid fa-gear"></i>
					</button>
					<ul class="dropdown-menu">
						<li><button class="dropdown-item" type="button"><i class="text-muted fa-solid fa-user-group"></i> Listing Agents</button></li>
						<li><button class="dropdown-item" type="button"><i class="text-muted fa-regular fa-map"></i> Locations</button></li>
						<li><button class="dropdown-item" type="button"><i class="text-muted fa-solid fa-map-pin"></i> Bayut Locations</button></li>
						<li><button class="dropdown-item" type="button"><i class="text-muted fa-solid fa-house-user"></i> Landlords</button></li>
						<li><button class="dropdown-item" type="button"><i class="text-muted fa-solid fa-helmet-safety"></i> Developers</button></li>
						<li><button class="dropdown-item" type="button"><i class="text-muted fa-solid fa-gear"></i> Settings</button></li>
					</ul>
				</div>
			</div>

		</div>


		<div class="container mt-5">
			<div class="d-flex align-items-center justify-content-between mb-4">
				<ul class="nav nav-pills flex-grow-1">
					<li class="nav-item">
						<a class="nav-link active" href="property_details.php">Property Details</a>
					</li>
					<li class="d-flex justify-content-center align-items-center mx-2">
						<i class="fa-solid fa-chevron-right"></i>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="media.php">Media</a>
					</li>
					<li class="d-flex justify-content-center align-items-center mx-2">
						<i class="fa-solid fa-chevron-right"></i>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="location.php">Location</a>
					</li>
					<li class="d-flex justify-content-center align-items-center mx-2">
						<i class="fa-solid fa-chevron-right"></i>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="publishing.php">Publishing</a>
					</li>
					<li class="d-flex justify-content-center align-items-center mx-2">
						<i class="fa-solid fa-chevron-right"></i>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="notes.php">Notes</a>
					</li>
					<li class="d-flex justify-content-center align-items-center mx-2">
						<i class="fa-solid fa-chevron-right"></i>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="documents.php">Documents</a>
					</li>
					<li class="d-flex justify-content-center align-items-center mx-2">
						<i class="fa-solid fa-chevron-right"></i>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="approval.php">Approval</a>
					</li>
					<li class="d-flex justify-content-center align-items-center mx-2">
						<i class="fa-solid fa-chevron-right"></i>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="completed.php">Completed</a>
					</li>
				</ul>
				<button class="btn btn-success mx-3">
					<i class="fas fa-save"></i> Save
				</button>
			</div>
		</div>

		<form action="media.php" method="POST">
			<div class="row p-3">
				<!-- Choose Property Type Section -->
				<div class="col-12 col-md-9 bg-light p-3">
					<h4>Choose Property Type</h4>
					<div class="row align-items-center">
						<!-- First Column: Commercial or Residential -->
						<div class="col-5 d-flex justify-content-center align-items-center gap-3 mb-3">
							<div class="text-center">
								<label>
									<input type="radio" name="property_type" value="residential" required>
									<img width="50" height="50" src="./assets/house.png" alt="house">
									<br>Residential
								</label>
							</div>
							<div class="text-center">
								<label>
									<input type="radio" name="property_type" value="commercial" required>
									<img width="50" height="50" src="./assets/apartment.png" alt="apartment">
									<br>Commercial
								</label>
							</div>
						</div>

						<!-- Vertical Line -->
						<div class="col-1 d-flex justify-content-center">
							<div class="vr" style="height: 100%;"></div>
						</div>

						<!-- Second Column: Rent or Sale -->
						<div class="col-6 d-flex justify-content-center align-items-center gap-3 mb-3">
							<div class="text-center">
								<label>
									<input type="radio" name="offer_type" value="rent" required>
									<img width="50" height="50" src="./assets/rent.png" alt="rent">
									<br>Rent
								</label>
							</div>
							<div class="text-center">
								<label>
									<input type="radio" name="offer_type" value="sale" required>
									<img width="50" height="50" src="./assets/sale.png" alt="sale">
									<br>Sale
								</label>
							</div>
						</div>
					</div>
				</div>


				<!-- Draft Property Section -->
				<div class="col-12 col-md-3 bg-light p-3">
					<h4>Draft Property</h4>
					<p class="text-muted">Last Update:</p>
					<p class="text-muted">Creation Date:</p>
					<p class="text-muted">Created By:</p>
				</div>
			</div>

			<div class="row mt-3 p-3">
				<div class="col-12 col-md-9 bg-light p-3">
					<h4>Specifications</h4>
					<div class="row mb-3">
						<div class="col-6">
							<label for="titleDeed">Title Deed</label><br>
							<input type="text" id="titleDeed" name="titleDeed" class="form-control">
						</div>
						<div class="col-6">
							<label for="propertyType">Property Type <span class="text-danger">*</span></label><br>
							<select name="propertyType" id="propertyType" class="form-control" required>
								<option value="" class="form-control">Please select</option>
								<option value="" class="form-control">Apartment</option>
								<option value="" class="form-control">Townhouse</option>
								<option value="" class="form-control">Villa</option>
								<option value="" class="form-control">Penthouse</option>
								<option value="" class="form-control">Residential Land</option>
								<option value="" class="form-control">Full Floor</option>
								<option value="" class="form-control">Bulk Units</option>
								<option value="" class="form-control">Compound</option>
								<option value="" class="form-control">Duplex</option>
								<option value="" class="form-control">Factory</option>
								<option value="" class="form-control">Farm</option>
								<option value="" class="form-control">Hotel Apartment</option>
								<option value="" class="form-control">Half Floor</option>
								<option value="" class="form-control">Labor Camp</option>
								<option value="" class="form-control">Land/Plot</option>
								<option value="" class="form-control">Office Space</option>
								<option value="" class="form-control">Business Centre</option>
								<option value="" class="form-control">Retail</option>
								<option value="" class="form-control">Restaurant</option>
								<option value="" class="form-control">Staff Accommodation</option>
								<option value="" class="form-control">Whole Building</option>
								<option value="" class="form-control">Shop</option>
								<option value="" class="form-control">Showroom</option>
								<option value="" class="form-control">Co-working Space</option>
								<option value="" class="form-control">Storage</option>
								<option value="" class="form-control">Warehouse</option>
								<option value="" class="form-control">Commercial Land</option>
								<option value="" class="form-control">Commercial Floor</option>
								<option value="" class="form-control">Commercial Building</option>
								<option value="" class="form-control">Residential Floor</option>
							</select>
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-6">
							<label for="size">Size (Sq.ft) <span class="text-danger">*</span></label><br>
							<input type="text" id="size" name="size" class="form-control" required>
						</div>
						<div class="col-6">
							<label for="unitNo">Unit No. <span class="text-danger">*</span></label><br>
							<input type="text" id="unitNo" name="unitNo" class="form-control" required>
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-6">
							<label for="bedrooms">No. of Bedrooms</label><br>
							<input type="number" value="0" id="bedrooms" name="bedrooms" class="form-control">
						</div>
						<div class="col-6">
							<label for="bathrooms">No. of Bathrooms</label><br>
							<input type="number" value="0" id="bathrooms" name="bathrooms" class="form-control">
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-6">
							<label for="parkingSpaces">No. Parking Spaces</label><br>
							<input type="number" id="parkingSpaces" name="parkingSpaces" class="form-control">
						</div>
						<div class="col-6">
							<label for="furnished">Select Furnished</label><br>
							<select name="furnished" id="furnished" class="form-control">
								<option value="">Please select</option>
								<option value="unfurnished">Unfurnished</option>
								<option value="semi-furnished">Semi-furnished</option>
								<option value="furnished">Furnished</option>
							</select>
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-6">
							<label for="totalPlotSize">Total Plot Size (Sq.ft)</label><br>
							<input type="text" id="totalPlotSize" name="totalPlotSize" class="form-control">
						</div>
						<div class="col-6">
							<label for="lotSize">Lot Size (Sq.ft)</label><br>
							<input type="text" id="lotSize" name="lotSize" class="form-control">
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-6">
							<label for="buildUpArea">Build-up Area</label><br>
							<input type="text" id="buildUpArea" name="buildUpArea" class="form-control">
						</div>
						<div class="col-6">
							<label for="layoutType">Layout Type</label><br>
							<input type="text" id="layoutType" name="layoutType" class="form-control">
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-6">
							<label for="projectName">Project Name</label><br>
							<input type="text" id="projectName" name="projectName" class="form-control">
						</div>
						<div class="col-6">
							<label for="projectStatus">Project Status</label><br>
							<input type="text" id="projectStatus" name="projectStatus" class="form-control">
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-6">
							<label for="ownership">Ownership</label><br>
							<select name="ownership" id="ownership" class="form-control">
								<option value="" class="form-control">Please select</option>
								<option value="free_hold" class="form-control">Free hold</option>
								<option value="none_hold" class="form-control">None hold</option>
								<option value="lease_hold" class="form-control">Lease hold</option>
							</select>
						</div>
						<div class="col-6">
							<label for="developers">Developers</label><br>
							<select name="developers" id="developers" class="form-control">
								<option value="" class="form-control">Please select</option>
								<?php
								foreach ($developers as $developer) {
									echo '<option value="' . $developer['value'] . '" class="form-control">' . $developer['name'] . '</option>';
								}
								?>
							</select>
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-6">
							<label for="buildYear">Build Year</label><br>
							<input type="text" id="buildYear" name="buildYear" class="form-control">
						</div>
					</div>
				</div>

				<div class="col-12 col-md-3 bg-light p-3">
					<h4>Management</h4>
					<div class="mb-3">
						<label for="reference">Reference</label>
						<input type="text" id="reference" class="form-control" disabled>
					</div>
					<div class="mb-3">
						<label for="listingAgent">Listing Agent</label>
						<input type="text" id="listingAgent" name="listingAgent" class="form-control">
					</div>
					<div class="mb-3">
						<label for="listingOwner">Listing Owner</label>
						<input type="text" id="listingOwner" name="listingOwner" class="form-control">
					</div>
					<div class="mb-3">
						<label for="landlordName">Landlord Name</label>
						<input type="text" id="landlordName" name="landlordName" class="form-control">
					</div>
					<div class="mb-3">
						<label for="landlordEmail">Landlord Email</label>
						<input type="text" id="landlordEmail" name="landlordEmail" class="form-control">
					</div>
					<div class="mb-3">
						<label for="landlordContact">Landlord Contact</label>
						<input type="text" id="landlordContact" name="landlordContact" class="form-control">
					</div>
					<div class="mb-3">
						<label for="availability">Availability</label><br>
						<select name="availability" id="availability" class="form-control">
							<option value="" class="form-control">Please select</option>
							<option value="available" class="form-control">Available</option>
							<option value="underOffer" class="form-control">Under Offer</option>
							<option value="reserved" class="form-control">Reserved</option>
							<option value="sold" class="form-control">Sold</option>
						</select>

					</div>
					<div class="mb-3">
						<label for="availableFrom">Available from</label>
						<input type="date" id="availableFrom" name="availableFrom" class="form-control">
					</div>
				</div>
			</div>

			<div class="row mt-3 p-3">
				<!-- Pricing Section -->
				<div class="col-12 col-md-9 bg-light p-3 rounded">
					<h4 class="mb-3">Pricing</h4>

					<div class="row mb-3">
						<div class="col-12 col-md-6">
							<label for="price" class="form-label">Price</label>
							<input type="text" id="price" name="price" class="form-control">
						</div>
						<div class="col-12 col-md-6">
							<label for="hidePrice" class="form-label">Hide Price (Property Finder only)</label>
							<input type="checkbox" id="hidePrice" name="hidePrice" class="form-check-input">
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-12 col-md-6">
							<label for="paymentMethod" class="form-label">Payment Method</label>
							<input type="text" id="paymentMethod" name="paymentMethod" class="form-control">
						</div>
						<div class="col-12 col-md-6">
							<label for="downPayment" class="form-label">Down Payment Price</label>
							<input type="text" id="downPayment" name="downPayment" class="form-control">
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-12 col-md-6">
							<label for="numCheques" class="form-label">No. of Cheques</label>
							<select id="numCheques" name="numCheques" class="form-control">
								<option value="">Please select</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
							</select>
						</div>
						<div class="col-12 col-md-6">
							<label for="serviceCharge" class="form-label">Service Charge</label>
							<input type="text" id="serviceCharge" name="serviceCharge" class="form-control">
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-12 col-md-6">
							<label for="financialStatus" class="form-label">Financial Status</label>
							<select id="financialStatus" name="financialStatus" class="form-control">
								<option value="">Please select</option>
								<option value="morgaged">Morgaged</option>
								<option value="cash">Cash</option>
							</select>
						</div>
					</div>
				</div>

				<!-- Property Permit Section -->
				<div class="col-12 col-md-3 bg-light p-3 rounded">
					<h4 class="mb-3">Property Permit</h4>

					<!-- Tabs Navigation -->
					<ul class="nav nav-tabs" id="permitTabs" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="nav-link active" id="rera-tab" data-bs-toggle="tab" href="#rera" role="tab">RERA</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link" id="dtcm-tab" data-bs-toggle="tab" href="#dtcm" role="tab">DTCM</a>
						</li>
					</ul>

					<!-- Tabs Content -->
					<div class="tab-content mt-3" id="permitTabsContent">
						<div class="tab-pane fade show active" id="rera" role="tabpanel">
							<div class="mb-3">
								<label for="reraPermitNumber" class="form-label">RERA Permit Number</label>
								<input type="text" id="reraPermitNumber" name="reraPermitNumber" class="form-control">
								<p class="text-muted text-sm">Once the reference is added it cannot be reassigned</p>
							</div>
							<div class="mb-3">
								<label for="reraPermitIssueDate" class="form-label">RERA Permit Issue Date</label>
								<input type="date" id="reraPermitIssueDate" name="reraPermitIssueDate" class="form-control">
							</div>
							<div class="mb-3">
								<label for="reraPermitExpirationDate" class="form-label">RERA Permit Expiration Date</label>
								<input type="date" id="reraPermitExpirationDate" name="reraPermitExpirationDate" class="form-control">
							</div>
						</div>
						<div class="tab-pane fade" id="dtcm" role="tabpanel">
							<div class="mb-3">
								<label for="dtcmPermitNumber" class="form-label">DTCM Permit Number</label>
								<input type="text" id="dtcmPermitNumber" name="dtcmPermitNumber" class="form-control">
								<p class="text-muted text-sm">Once the reference is added it cannot be reassigned</p>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row mt-3 p-3">
				<div class="col-12 col-md-6 bg-light p-3 rounded">
					<h4 class="mb-3">Description</h4>

					<!-- Tabs Navigation -->
					<ul class="nav nav-tabs mb-3" id="languageTabs" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="nav-link active" id="english-tab" data-bs-toggle="tab" href="#english" role="tab">English</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link" id="arabic-tab" data-bs-toggle="tab" href="#arabic" role="tab">Arabic</a>
						</li>
					</ul>

					<!-- Tabs Content -->
					<div class="tab-content" id="languageTabsContent">
						<div class="tab-pane fade show active" id="english" role="tabpanel">
							<div class="mb-3">
								<label for="title-english" class="form-label">Title <span class="text-danger">*</span></label>
								<input type="text" id="title-english" class="form-control" required>
							</div>
							<div class="mb-3">
								<label for="description-english" class="form-label">Description <span class="text-danger">*</span></label>
								<textarea id="description-english" class="form-control" placeholder="Enter English description" required></textarea>
							</div>
						</div>
						<div class="tab-pane fade" id="arabic" role="tabpanel">
							<div class="mb-3">
								<label for="title-arabic" class="form-label">Title</label>
								<input type="text" id="title-arabic" class="form-control">
							</div>
							<div class="mb-3">
								<label for="description-arabic" class="form-label">Description</label>
								<textarea id="description-arabic" class="form-control" placeholder="Enter Arabic description"></textarea>
							</div>
						</div>
					</div>

					<div class="mb-3">
						<p class="h5 mb-3">Select Amenities</p>

						<!-- Update Amenities Button -->
						<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#amenitiesModal">
							Update Amenities
						</button>

						<!-- Selected Amenities Section (hidden initially) -->
						<div id="selectedAmenities" class="mt-3"></div>
					</div>

					<!-- Modal Structure -->
					<div class="modal fade" id="amenitiesModal" tabindex="-1" aria-labelledby="amenitiesModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<!-- Modal Header -->
								<div class="modal-header">
									<h5 class="modal-title" id="amenitiesModalLabel">Select Amenities</h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>

								<!-- Modal Body -->
								<div class="modal-body">
									<div class="row">
										<!-- Private Amenities Section -->
										<div class="col-12">
											<p class="h6 mb-3">Private Amenities</p>
											<div id="amenities-container" class="row gy-2">
												<!-- Amenities will be inserted here by JavaScript -->
											</div>
										</div>
									</div>
								</div>




								<!-- Modal Footer -->
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
									<button type="button" class="btn btn-primary" id="saveAmenities">Save Amenities</button>
								</div>
							</div>
						</div>
					</div>



				</div>
			</div>

			<div class="d-flex justify-content-end gap-3 mt-3 mb-3">
				<button type="button" class="btn btn-success">
					<i class="fa fa-save"></i> Save
				</button>
				<button type="submit" onclick="location.href='media.php'" class="btn btn-primary">
					<i class="fa fa-arrow-right"></i> Continue
				</button>
			</div>
		</form>

	</div>

	<script>
		// Define an array of amenities
		const amenities = [{
				id: 'gv',
				label: 'Golf view'
			},
			{
				id: 'cw',
				label: 'City view'
			},
			{
				id: 'no',
				label: 'North orientation'
			},
			{
				id: 'so',
				label: 'South orientation'
			},
			{
				id: 'eo',
				label: 'East orientation'
			},
			{
				id: 'wo',
				label: 'West orientation'
			},
			{
				id: 'ns',
				label: 'Near school'
			},
			{
				id: 'ho',
				label: 'Near hospital'
			},
			{
				id: 'tr',
				label: 'Terrace'
			},
			{
				id: 'nm',
				label: 'Near mosque'
			},
			{
				id: 'sm',
				label: 'Near supermarket'
			},
			{
				id: 'ml',
				label: 'Near mall'
			},
			{
				id: 'pt',
				label: 'Near public transportation'
			},
			{
				id: 'mo',
				label: 'Near metro'
			},
			{
				id: 'vt',
				label: 'Near veterinary'
			},
			{
				id: 'bc',
				label: 'Beach access'
			},
			{
				id: 'pk',
				label: 'Public parks'
			},
			{
				id: 'rt',
				label: 'Near restaurants'
			},
			{
				id: 'ng',
				label: 'Near Golf'
			},
			{
				id: 'ap',
				label: 'Near airport'
			},
			{
				id: 'cs',
				label: 'Concierge Service'
			},
			{
				id: 'ss',
				label: 'Spa'
			},
			{
				id: 'sy',
				label: 'Shared Gym'
			},
			{
				id: 'ms',
				label: 'Maid Service'
			},
			{
				id: 'wc',
				label: 'Walk-in Closet'
			},
			{
				id: 'ht',
				label: 'Heating'
			},
			{
				id: 'gf',
				label: 'Ground floor'
			},
			{
				id: 'sv',
				label: 'Server room'
			},
			{
				id: 'dn',
				label: 'Pantry'
			},
			{
				id: 'ra',
				label: 'Reception area'
			},
			{
				id: 'vp',
				label: 'Visitors parking'
			},
			{
				id: 'op',
				label: 'Office partitions'
			},
			{
				id: 'sh',
				label: 'Core and Shell'
			},
			{
				id: 'cd',
				label: 'Children daycare'
			},
			{
				id: 'cl',
				label: 'Cleaning services'
			},
			{
				id: 'nh',
				label: 'Near Hotel'
			},
			{
				id: 'cr',
				label: 'Conference room'
			},
			{
				id: 'bl',
				label: 'View of Landmark'
			},
			{
				id: 'pr',
				label: 'Children Play Area'
			},
			{
				id: 'bh',
				label: 'Beach Access'
			}
		];


		// Function to generate the amenities HTML
		function generateAmenities(amenities) {
			return amenities.map(amenity => `
        <div class="col-12 form-check">
            <input type="checkbox" class="form-check-input hidden-checkbox" id="${amenity.id}">
            <label class="form-check-label styled-label" for="${amenity.id}">${amenity.label}</label>
        </div>
    `).join('');
		}

		// Insert the amenities into the container
		document.getElementById('amenities-container').innerHTML = generateAmenities(amenities);
	</script>

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
	<script src="./js/script.js"></script>
	<script>
		document.getElementById('saveAmenities').addEventListener('click', function() {
			let selectedAmenities = [];

			// Get all checkboxes
			const checkboxes = document.querySelectorAll('#amenitiesModal input[type="checkbox"]');

			// Loop through checkboxes and find the ones that are checked
			checkboxes.forEach(function(checkbox) {
				if (checkbox.checked) {
					// Get the label text corresponding to the checkbox
					const label = document.querySelector(`label[for=${checkbox.id}]`).innerText;
					selectedAmenities.push(label);
				}
			});

			// If amenities are selected, hide the button and show the selected amenities
			if (selectedAmenities.length > 0) {
				// Hide the Update Amenities button
				document.querySelector('button[data-bs-target="#amenitiesModal"]').style.display = 'none';

				// Show the selected amenities
				const selectedAmenitiesDiv = document.getElementById('selectedAmenities');
				selectedAmenitiesDiv.innerHTML = `<p class="h6 mb-2">Selected Amenities:</p><ul class="list-unstyled"></ul>`;
				selectedAmenities.forEach(function(amenity) {
					selectedAmenitiesDiv.querySelector('ul').innerHTML += `<li>${amenity}</li>`;
				});
			}

			// Close the modal
			const modal = bootstrap.Modal.getInstance(document.getElementById('amenitiesModal'));
			modal.hide();
		});
	</script>

</body>

</html>