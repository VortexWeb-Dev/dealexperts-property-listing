<?php
require_once __DIR__ . '/crest/crest.php';
require_once __DIR__ . '/crest/settings.php';

$data = $_POST;

if ($data) {
	echo '<pre>';
	print_r($data);
	echo '</pre>';

	// CRest::call('crm.item.add', [
	// 	'entityTypeId' => PROPERTY_LISTING_ENTITY_TYPE_ID,
	// 	'fields' => [
	// 		'ufCrm46Notes' => $data['notes'],
	// 	]
	// ]);
	// header('Location: notes.php');
}

?>
<?php include 'includes/header.php'; ?>

<!-- Main Content Area -->
<div class="flex-grow-1" style="height: 100vh; overflow-y: auto;">
	<!-- Fixed Topbar -->
	<?php include 'includes/topbar.php'; ?>
	<div class="container-fluid py-4">
		<div class="container mt-5">
			<div class="d-flex align-items-center justify-content-between mb-4">
				<ul class="nav nav-pills flex-grow-1 gap-1">
					<li class="nav-item">
						<a class="nav-link completed" href="create_listing.php">Property Details</a>
					</li>
					<li class="d-flex justify-content-center align-items-center mx-2">
						<i class="fa-solid fa-chevron-right"></i>
					</li>
					<li class="nav-item">
						<a class="nav-link active" href="notes.php">Notes</a>
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
			</div>
		</div>

		<div class="container mt-5">
			<div class="row p-3 bg-light rounded border">
				<!-- Heading -->
				<div class="col-12 mb-3">
					<h5>Write a New Note</h5>
				</div>

				<form method="POST" class="col-12" >
					<!-- Text Editor-like area -->
					<div class="col-12">
						<!-- Formatting toolbar -->
						<div class="d-flex mb-2 align-items-center">
							<select class="form-select form-select-sm w-auto me-2">
								<option selected>Normal</option>
								<option value="1">Heading 1</option>
								<option value="2">Heading 2</option>
							</select>
							<button class="btn btn-light btn-sm me-2"><i class="bi bi-type-bold"></i></button>
							<button class="btn btn-light btn-sm me-2"><i class="bi bi-type-italic"></i></button>
							<button class="btn btn-light btn-sm me-2"><i class="bi bi-type-underline"></i></button>
							<button class="btn btn-light btn-sm me-2"><i class="bi bi-link"></i></button>
							<button class="btn btn-light btn-sm me-2"><i class="bi bi-list-ol"></i></button>
							<button class="btn btn-light btn-sm me-2"><i class="bi bi-list-ul"></i></button>
							<button class="btn btn-light btn-sm"><i class="bi bi-text-paragraph"></i></button>
						</div>

						<!-- Textarea for writing the note -->
						<textarea class="form-control" rows="6" placeholder="Write your note here..." name="note"></textarea>
					</div>

					<!-- Add button -->
					<div class="col-12 mt-3">
						<button type="submit" class="btn btn-primary">Add</button>
					</div>
				</form>

			</div>
		</div>

		<!-- Include Bootstrap Icons -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">


			<div class="d-flex justify-content-between align-items-center mt-3 mb-3">
				<a href="create_listing.php" class="btn btn-outline-primary">
					<i class="fa fa-arrow-left"></i> Previous
				</a>
				<div class="d-flex gap-3">
					<button type="button" class="btn btn-success">
						<i class="fa fa-save"></i> Save
					</button>
					<a href="documents.php" class="btn btn-primary">
						<i class="fa fa-arrow-right"></i> Continue
					</a>
				</div>
			</div>
	</div>
</div>

<?php include 'includes/footer.php'; ?>