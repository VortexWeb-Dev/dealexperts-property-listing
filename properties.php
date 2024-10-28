<?php
require_once(__DIR__ . '/crest/crest.php');
require_once(__DIR__ . '/crest/settings.php');

function fetchProperties($filter = null)
{

	$filterConditions = [];

	if ($filter === 'draft') {
		$filterConditions['ufCrm42Status'] = 'DRAFT';
	} elseif ($filter === 'live') {
		$filterConditions['ufCrm42Status'] = 'LIVE';
	} elseif ($filter === 'pending') {
		$filterConditions['ufCrm42Status'] = 'PENDING';
	} elseif ($filter === 'archived') {
		$filterConditions['ufCrm42Status'] = 'ARCHIVED';
	}

	// if (!empty($filter['refId'])) {
	// 	$filterConditions['ufCrm42RefId'] = $filter['refId'];
	// }
	if (!empty($filter['community'])) {
		$filterConditions['ufCrm42PfCommunity'] = $filter['community'];
	}
	if (!empty($filter['subCommunity'])) {
		$filterConditions['ufCrm42PfSubCommunity'] = $filter['subCommunity'];
	}
	if (!empty($filter['building'])) {
		$filterConditions['ufCrm42PfBuilding'] = $filter['building'];
	}
	if (!empty($filter['unitNo'])) {
		$filterConditions['ufCrm42UnitNo'] = $filter['unitNo'];
	}
	if (!empty($filter['permit'])) {
		$filterConditions['ufCrm42Permit'] = $filter['permit'];
	}
	if (!empty($filter['listingOwner'])) {
		$filterConditions['ufCrm42ListingOwner'] = $filter['listingOwner'];
	}
	if (!empty($filter['listingTitle'])) {
		$filterConditions['ufCrm42ListingTitle'] = $filter['listingTitle'];
	}
	if (!empty($filter['category'])) {
		$filterConditions['ufCrm42Category'] = $filter['category'];
	}
	if (!empty($filter['propertyType'])) {
		$filterConditions['ufCrm42PropertyType'] = $filter['propertyType'];
	}
	if (!empty($filter['saleRent'])) {
		$filterConditions['ufCrm42SaleRent'] = $filter['saleRent'];
	}
	if (!empty($filter['listingAgents'])) {
		$filterConditions['ufCrm42ListingAgents'] = $filter['listingAgents'];
	}
	if (!empty($filter['landlord'])) {
		$filterConditions['ufCrm42Landlord'] = $filter['landlord'];
	}
	if (!empty($filter['landlordEmail'])) {
		$filterConditions['ufCrm42LandlordEmail'] = $filter['landlordEmail'];
	}
	if (!empty($filter['landlordPhone'])) {
		$filterConditions['ufCrm42LandlordPhone'] = $filter['landlordPhone'];
	}
	if (!empty($filter['bedrooms'])) {
		$filterConditions['ufCrm42Bedroom'] = $filter['bedrooms'];
	}
	if (!empty($filter['developers'])) {
		$filterConditions['ufCrm42Developers'] = $filter['developers'];
	}
	if (!empty($filter['price'])) {
		$filterConditions['ufCrm42Price'] = $filter['price'];
	}
	if (!empty($filter['portals'])) {
		$filterConditions['ufCrm42Portals'] = $filter['portals'];
	}

	// Call Bitrix24 API to fetch properties with filter conditions
	$response = CRest::call('crm.item.list', [
		'entityTypeId' => PROPERTY_LISTING_ENTITY_TYPE_ID,
		'filter' => $filterConditions
	]);

	return $response['result']['items'] ?? [];
}

// Check if a filter is set in the URL
$filter = $_GET['filter'] ?? null;

// Fetch filtered properties
$properties = fetchProperties($filter);

// echo '<pre>';
// print_r($properties);
// echo '</pre>';

// Store properties in session
session_start();
$_SESSION['properties'] = $properties;

// Function to fetch property details by ID
function fetchPropertyDetails($id)
{
	$response = CRest::call('crm.item.get', [
		'entityTypeId' => PROPERTY_LISTING_ENTITY_TYPE_ID,
		'id' => $id
	]);

	return $response['result']['item'] ?? [];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Property Listing</title>
	<!-- Bootstrap CSS -->
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<!-- Chart.js -->
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
	<!-- Custom CSS -->
	<link rel="stylesheet" href="/styles/app.css">
	<style>
		.settings-menu,
		.bulk-options-menu {
			display: none;
		}

		.dropdown-menu.show {
			display: block;
		}

		.main-content {
			background-color: #ffffff;
			border-radius: 0.5rem;
			box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
			/* margin: 1rem; */
			padding: 1.5rem;
		}

		.text-truncate-two-lines {
			display: -webkit-box;
			-webkit-line-clamp: 2;
			-webkit-box-orient: vertical;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		.dropdown-menu {
			position: fixed !important;
			z-index: 1050 !important;
		}

		.overflow-auto {
			max-height: 80vh;
		}
	</style>
	<script>
		function toggleCheckboxes(source) {
			const checkboxes = document.querySelectorAll('input[name="property_ids[]"]');
			checkboxes.forEach(checkbox => {
				checkbox.checked = source.checked;
			});
		}
	</script>
</head>

<body>
	<div class="d-flex">
		<!-- Sticky Left Sidebar -->
		<?php include 'includes/sidebar.php'; ?>

		<!-- Main Content Area -->
		<div class="flex-grow-1" style="height: 100vh; overflow-y: auto;">
			<!-- Fixed Topbar -->
			<?php include 'includes/topbar.php'; ?>

			<div class="container px-3 py-2 px-md-5 py-md-4">
				<h2 class="display-10 fw-bold text-primary container">Properties</h2>
				<!-- Table Section -->
				<div class="table-card">
					<div class="position-relative overflow-auto" style="max-height: 500px; scrollbar-width: thin; scrollbar-color: #EFF2F5 #fff;">
						<table class="table table-hover align-middle shadow-sm table-borderless">
							<thead class="">
								<tr>
									<th style="white-space: nowrap; padding: 10px 20px;">
										<div class="form-check d-flex justify-content-center align-items-center">
											<input class="form-check-input" type="checkbox" id="select-all" onclick="toggleCheckboxes(this)">
											<label class="form-check-label" for="select-all"></label>
										</div>
									</th>
									<th style="white-space: nowrap; padding: 10px 20px; color:#334155; font-weight: 600;" scope="col">Actions</th>
									<th style="white-space: nowrap; padding: 10px 20px; color:#334155; font-weight: 600;" scope="col">Reference</th>
									<th style="white-space: nowrap; padding: 10px 20px; min-width: 450px; color:#334155; font-weight: 600;" scope="col">Property</th>
									<th style="white-space: nowrap; padding: 10px 20px; min-width: 150px; color:#334155; font-weight: 600;" scope="col">Details</th>
									<th style="white-space: nowrap; padding: 10px 20px; color:#334155; font-weight: 600;" scope="col">Type</th>
									<th style="white-space: nowrap; padding: 10px 20px; min-width: 150px; color:#334155; font-weight: 600;" scope="col">Price</th>
									<th style="white-space: nowrap; padding: 10px 20px; color:#334155; font-weight: 600;" scope="col">Location</th>
									<th style="white-space: nowrap; padding: 10px 20px; min-width: 150px; color:#334155; font-weight: 600;" scope="col">Agent</th>
									<th style="white-space: nowrap; padding: 10px 20px; min-width: 150px; color:#334155; font-weight: 600;" scope="col">Owner</th>
								</tr>
							</thead>

							<tbody class="">
								<form action="export.php" method="POST" id="exportForm">
									<?php foreach ($properties as $property): ?>
										<tr>
											<td style="padding: 10px 20px;">
												<div class="form-check d-flex align-items-center justify-content-center">
													<input class="form-check-input" type="checkbox" name="property_ids[]" value="<?php echo htmlspecialchars($property['id']); ?>">
													<label class="form-check-label"></label>
												</div>
											</td>

											<td style="padding: 10px 20px;">
												<div class="dropdown">
													<button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
														<i class="fa-solid fa-ellipsis-vertical"></i>
													</button>
													<ul class="dropdown-menu shadow absolute z-10" style="max-height: 50vh; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #6B7280 #f9fafb; font-size:medium;">
														<li><a class="dropdown-item" href="edit-property.php?id=<?php echo $property['id']; ?>"><i class="fa-solid fa-edit me-2"></i>Edit</a></li>
														<li><a class="dropdown-item" href="view_listing.php?id=<?php echo $property['id']; ?>"><i class="fa-solid fa-eye me-2"></i>View Details</a></li>
														<li><a class="dropdown-item" href="#" onclick="copyLink('<?php echo $property['id']; ?>')"><i class="fa-solid fa-link me-2"></i>Copy Link</a></li>
														<li><a class="dropdown-item" href="download-property.php?id=<?php echo $property['id']; ?>"><i class="fa-solid fa-download me-2"></i>Download PDF</a></li>
														<li><a class="dropdown-item" href="xml.php?propertyId=<?php echo $property['id']; ?>"><i class="fa-solid fa-upload me-2"></i>Publish</a></li>
														<li><a class="dropdown-item" href="make-exclusive.php?id=<?php echo $property['id']; ?>"><i class="fa-solid fa-lock me-2"></i>Make Exclusive</a></li>
														<li><a class="dropdown-item" href="make-featured.php?id=<?php echo $property['id']; ?>"><i class="fa-solid fa-star me-2"></i>Make Featured</a></li>
														<li><a class="dropdown-item" href="make-business-class.php?id=<?php echo $property['id']; ?>"><i class="fa-solid fa-briefcase me-2"></i>Make Business Class</a></li>
														<li><a class="dropdown-item" href="duplicate-listing.php?id=<?php echo $property['id']; ?>"><i class="fa-solid fa-copy me-2"></i>Duplicate Listing</a></li>
														<li><a class="dropdown-item" href="refresh-listing.php?id=<?php echo $property['id']; ?>"><i class="fa-solid fa-sync me-2"></i>Refresh Listing</a></li>
														<li><a class="dropdown-item" href="unpublish.php?id=<?php echo $property['id']; ?>"><i class="fa-solid fa-archive me-2"></i>Unpublish (Archive)</a></li>
														<li><a class="dropdown-item" href="#" onclick="copyLinkAsLoggedInAgent('<?php echo $property['id']; ?>')"><i class="fa-solid fa-link me-2"></i>Copy Link as Logged in Agent</a></li>
														<li><a class="dropdown-item" href="download-pdf-as-loggedin-agent.php?id=<?php echo $property['id']; ?>"><i class="fa-solid fa-download me-2"></i>Download PDF as Logged in Agent</a></li>
														<li>
															<hr class="dropdown-divider">
														</li>
														<li><a class="dropdown-item text-danger" href="delete-property.php?id=<?php echo $property['id']; ?>"><i class="fa-solid fa-trash me-2"></i>Delete</a></li>
													</ul>
												</div>
											</td>
											<td class="text-truncate" style="max-width: 150px; padding: 10px 20px;">
												<?php
												if (isset($property['ufCrm42ReferenceNumber']) && $property['ufCrm42ReferenceNumber'] !== null && $property['ufCrm42ReferenceNumber'] !== '') {
													echo htmlspecialchars($property['ufCrm42ReferenceNumber']);
												} else {
													echo 'N/A';
												}
												?>
											</td>
											<td style="padding: 10px 20px; min-width: 200px;">
												<div class="d-flex align-items-center">
													<img src="<?= htmlspecialchars($property['ufCrm42Photos'][0] ?? $property['ufCrm42PhotosFiles'][0]['urlMachine']) ?>"
														class="me-3"
														style="width: 60px; height: 60px; object-fit: cover;"
														alt="Property Image">
													<div>
														<h6 class="mb-0"><?= htmlspecialchars($property['ufCrm42TitleEn']) ?></h6>
														<small class="text-muted d-inline-block text-truncate" style="max-width: 150px;">
															<?= htmlspecialchars($property['ufCrm42DescriptionEn']) ?>
														</small>
													</div>
												</div>
											</td>
											<td style="padding: 10px 20px;">
												<div class="d-flex flex-column gap-1 no-wrap" style="color: #64748b;">
													<span style="font-size:smaller; font-weight: 600;" class="mb-1"><?= htmlspecialchars($property['ufCrm42Size']) ?> sq ft</span>
													<div class="d-flex justify-content-start gap-1">
														<span class="text-secondary font-size-small me-1"><i class="fa-solid fa-bath me-1"></i><?= htmlspecialchars($property['ufCrm42Bathroom']) ?></span>
														<span class="text-secondary font-size-small"><i class="fa-solid fa-bed me-1"></i><?= htmlspecialchars($property['ufCrm42Bedroom']) ?></span>
													</div>
												</div>
											</td>
											<td style="padding: 10px 20px;">
												<span class="badge bg-primary"><?= htmlspecialchars($property['ufCrm42PropertyType']) ?></span>
												<?php if (isset($property['status']) && $property['status']): ?>
													<span class="badge bg-success"><?= htmlspecialchars($property['status']) ?></span>
												<?php endif; ?>
											</td>
											<td style="padding: 10px 20px;">
												<h6 style="color:#334155; font-weight: 600; font-size:smaller" class="mb-0">AED <?= number_format(htmlspecialchars($property['ufCrm42Price'])) ?></h6>
												<small class="text-secondary font-size-small">
													<?= htmlspecialchars(in_array($property['ufCrm42OfferingType'], ['RR', 'CR']) ? 'Rent' : 'Sale') ?>
												</small>
											</td>
											<td class="text-truncate" style="padding: 10px 20px;">
												<span style="color:#334155; font-weight: 600; font-size:smaller"><?= htmlspecialchars($property['ufCrm42PfCity']) ?> - <?= htmlspecialchars($property['ufCrm42PfCommunity']) ?> - <?= htmlspecialchars($property['ufCrm42PfSubCommunity']) ?></span>
											</td>
											<td style="padding: 10px 20px;">
												<span style="color:#334155; font-weight: 600; font-size:smaller"><?= htmlspecialchars($property['ufCrm42AgentName']) ?></span>
											</td>
											<td class="text-truncate" style="padding: 10px 20px;">
												<span style="color:#334155; font-weight: 600; font-size:smaller"><?= htmlspecialchars($property['ufCrm42ListingOwner']) ?></span>
											</td>
										</tr>
									<?php endforeach; ?>
								</form>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		function deleteSelectedProperties() {
			// Gather all checked checkboxes
			var checkboxes = document.querySelectorAll('input[name="property_ids[]"]:checked');
			var propertyIds = Array.from(checkboxes).map(checkbox => checkbox.value);

			if (propertyIds.length === 0) {
				alert('No properties selected');
				return;
			}

			// Send selected IDs to delete.php
			var xhr = new XMLHttpRequest();
			xhr.open('POST', 'delete.php', true);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.onload = function() {
				if (xhr.status === 200) {
					// Handle the response if needed
					console.log('Response:', xhr.responseText);
					// alert('Properties deleted successfully');
					location.reload();
				} else {
					console.error('Error:', xhr.statusText);
				}
			};
			xhr.send('property_ids=' + encodeURIComponent(JSON.stringify(propertyIds)));
		}

		function publishSelectedProperties() {
			// Gather all checked checkboxes
			var checkboxes = document.querySelectorAll('input[name="property_ids[]"]:checked');
			var propertyIds = Array.from(checkboxes).map(checkbox => checkbox.value);

			if (propertyIds.length === 0) {
				alert('No properties selected');
				return;
			}

			// Create the URL for publishing
			var url = `../xml.php?property_ids=${encodeURIComponent(JSON.stringify(propertyIds))}`;

			// Redirect to the constructed URL
			window.location.href = url; // This will navigate to the xml.php with the query parameters
		}

		function exportProperties() {
			// Gather all checked checkboxes
			var checkboxes = document.querySelectorAll('input[name="property_ids[]"]:checked');
			var propertyIds = Array.from(checkboxes).map(checkbox => checkbox.value);

			if (propertyIds.length === 0) {
				alert('No properties selected');
				return;
			}

			document.getElementById('exportForm').submit()
		}

		function publishSelectedPropertiesToBayut() {
			// Gather all checked checkboxes
			var checkboxes = document.querySelectorAll('input[name="property_ids[]"]:checked');
			var propertyIds = Array.from(checkboxes).map(checkbox => checkbox.value);

			if (propertyIds.length === 0) {
				alert('No properties selected');
				return;
			}

			// Create the URL for publishing
			var url = `../xml.php?platform=bayut&property_ids=${encodeURIComponent(JSON.stringify(propertyIds))}`;

			// Redirect to the constructed URL
			window.location.href = url; // This will navigate to the xml.php with the query parameters
		}

		function publishSelectedPropertiesToDubizzle() {
			// Gather all checked checkboxes
			var checkboxes = document.querySelectorAll('input[name="property_ids[]"]:checked');
			var propertyIds = Array.from(checkboxes).map(checkbox => checkbox.value);

			if (propertyIds.length === 0) {
				alert('No properties selected');
				return;
			}

			// Create the URL for publishing
			var url = `../xml.php?platform=dubizzle&property_ids=${encodeURIComponent(JSON.stringify(propertyIds))}`;

			// Redirect to the constructed URL
			window.location.href = url; // This will navigate to the xml.php with the query parameters
		}

		function publishSelectedPropertiesToPF() {
			// Gather all checked checkboxes
			var checkboxes = document.querySelectorAll('input[name="property_ids[]"]:checked');
			var propertyIds = Array.from(checkboxes).map(checkbox => checkbox.value);

			if (propertyIds.length === 0) {
				alert('No properties selected');
				return;
			}

			// Create the URL for publishing
			var url = `../xml.php?property_ids=${encodeURIComponent(JSON.stringify(propertyIds))}`;

			// Redirect to the constructed URL
			window.location.href = url; // This will navigate to the xml.php with the query parameters
		}

		function unPublishSelectedProperties() {
			// Gather all checked checkboxes
			var checkboxes = document.querySelectorAll('input[name="property_ids[]"]:checked');
			var propertyIds = Array.from(checkboxes).map(checkbox => checkbox.value);

			if (propertyIds.length === 0) {
				alert('No properties selected');
				return;
			}

			// Send selected IDs to delete.php
			var xhr = new XMLHttpRequest();
			xhr.open('POST', 'unpublish.php', true);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.onload = function() {
				if (xhr.status === 200) {

					console.log('Response:', xhr.responseText);
					alert('Success: Properties unpublished successfully');

				} else {
					console.error('Error:', xhr.statusText);
				}
			};
			xhr.send('property_ids=' + encodeURIComponent(JSON.stringify(propertyIds)));
		}

		function transferSelectedPropertiesToAgent() {
			// Gather all checked checkboxes
			var checkboxes = document.querySelectorAll('input[name="property_ids[]"]:checked');
			var propertyIds = Array.from(checkboxes).map(checkbox => checkbox.value);

			// Ensure the modal is open and agent_id exists
			var agent_id = document.querySelector('#agentModal #agent_id'); // Select agent_id inside the modal

			// if (!agent_id || !agent_id.value) {
			// 	alert('Please select an agent.');
			// 	return;
			// }

			if (propertyIds.length === 0) {
				alert('No properties selected');
				return;
			}

			// Build the query string
			var queryParams = new URLSearchParams({
				// agent_id: agent_id.value, // Get the value of the agent_id input
				property_ids: propertyIds.join(',') // Join property IDs with commas
			});

			// Redirect to transfer_agent.php with the query parameters
			window.location.href = '../transfer_agent.php?' + queryParams.toString();
		}

		function selectAndAddPropertiesToAgentTransfer() {
			// Gather all checked checkboxes
			var checkboxes = document.querySelectorAll('input[name="property_ids[]"]:checked');
			var propertyIds = Array.from(checkboxes).map(checkbox => checkbox.value);

			// Assign property IDs to the hidden input field
			document.getElementById('transferAgentPropertyIds').value = propertyIds.join(',');

			// // Check if the agentSelect dropdown has a value
			// var agentSelect = document.getElementById('agentSelect');
			// console.log("Selected Agent ID: " + agentSelect.value);

			// if (!agentSelect.value) {
			// 	alert("Please select an agent before proceeding.");
			// }
		}

		function selectAndAddPropertiesToOwnerTransfer() {
			// Gather all checked checkboxes
			var checkboxes = document.querySelectorAll('input[name="property_ids[]"]:checked');
			var propertyIds = Array.from(checkboxes).map(checkbox => checkbox.value);

			// Assign property IDs to the hidden input field
			document.getElementById('transferOwnerPropertyIds').value = propertyIds.join(',');

			// // Check if the agentSelect dropdown has a value
			// var ownerName = document.getElementById('ownerName');
			// console.log("Owner Name: " + ownerName);

			// if (!ownerName.value) {
			// 	alert("Please enter the owner name before proceeding.");
			// }
		}



		function copyLink(propertyId) {
			var url = `${window.location.origin}/projects/property-listing/view_listing.php?id=${propertyId}`;

			navigator.clipboard.writeText(url).then(function() {
				alert('Link copied to clipboard');
			}).catch(function(err) {
				console.error('Failed to copy the link: ', err);
			});
		}
	</script>

	<!-- Bootstrap JS and dependencies -->
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
	<script src="./js/script.js">
	</script>
</body>

</html>