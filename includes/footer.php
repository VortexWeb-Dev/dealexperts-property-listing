</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="./js/script.js"></script>
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
				<input type="checkbox" class="form-check-input hidden-checkbox" id="${amenity.id}" name="amenities[]" value="${amenity.label}">
				<label class="form-check-label styled-label" for="${amenity.id}">${amenity.label}</label>
			</div>
		`).join('');

	}

	// Insert the amenities into the container
	document.getElementById('amenities-container').innerHTML = generateAmenities(amenities);
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

	document.getElementById("bayutEnableFull").addEventListener("change", function() {
		if (this.checked) {
			document.getElementById("bayutEnable").checked = true;
			document.getElementById("dubizleEnable").checked = true;
		}
	});


	// JavaScript to handle image preview
	const floorPlanInput = document.getElementById('floorPlan');
	const floorPlanPreview = document.getElementById('floorPlanPreview');
	const selectedFloorPlan = document.getElementById('selectedFloorPlan');

	const photoInput = document.getElementById('photo');
	const photoPreview = document.getElementById('photoPreview');
	const selectedPhoto = document.getElementById('selectedPhoto');

	floorPlanInput.addEventListener('change', function(event) {
		const file = event.target.files[0];

		if (file && file.type.startsWith('image/') && file.size <= 2 * 1024 * 1024) {
			const reader = new FileReader();

			reader.onload = function(e) {
				selectedFloorPlan.src = e.target.result; // Set the image src
				floorPlanPreview.style.display = 'block'; // Show the preview
			};

			reader.readAsDataURL(file); // Read the file as a data URL
		} else {
			floorPlanPreview.style.display = 'none'; // Hide the preview if invalid
			alert('Please select a valid image file (Max size: 2MB)');
		}
	});

	photoInput.addEventListener('change', function(event) {
		const file = event.target.files[0];

		if (file && file.type.startsWith('image/') && file.size <= 2 * 1024 * 1024) {
			const reader = new FileReader();

			reader.onload = function(e) {
				selectedPhoto.src = e.target.result; // Set the image src
				photoPreview.style.display = 'block'; // Show the preview
			};

			reader.readAsDataURL(file); // Read the file as a data URL
		} else {
			photoPreview.style.display = 'none'; // Hide the preview if invalid
			alert('Please select a valid image file (Max size: 2MB)');
		}
	});

	const pfLocation = document.getElementById('propertyLocation');

	const pfCity = document.getElementById('propertyCity');
	const pfCommunity = document.getElementById('propertyCommunity');
	const pfSubCommunity = document.getElementById('propertySubCommunity');
	const pfTower = document.getElementById('propertyTower');

	const bayutLocation = document.getElementById('bayutLocation');
	const bayutCity = document.getElementById('bayutCity');
	const bayutCommunity = document.getElementById('bayutCommunity');
	const bayutSubCommunity = document.getElementById('bayutSubCommunity');
	const bayutTower = document.getElementById('bayutTower');

	pfLocation.addEventListener('change', function() {
		const location = pfLocation.value;

		pfCity.value = location.split(' - ')[0];
		pfCommunity.value = location.split(' - ')[1];
		pfSubCommunity.value = location.split(' - ')[2];
		pfTower.value = location.split(' - ')[3];
	});

	bayutLocation.addEventListener('change', function() {
		const location = bayutLocation.value;

		bayutCity.value = location.split(' - ')[0];
		bayutCommunity.value = location.split(' - ')[1];
		bayutSubCommunity.value = location.split(' - ')[2];
		bayutTower.value = location.split(' - ')[3];
	});
</script>

</body>

</html>