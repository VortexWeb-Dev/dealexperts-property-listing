const leftChartData = {
  labels: ["Sales", "Rent", "Property Finder", "Bayut", "Website"],
  datasets: [
    {
      label: "Left Chart",
      data: [1, 0, 1, 1, 1],
      backgroundColor: [
        "rgba(54, 162, 235, 0.6)",
        "rgba(75, 192, 192, 0.6)",
        "rgba(153, 102, 255, 0.6)",
        "rgba(255, 159, 64, 0.6)",
        "rgba(255, 205, 86, 0.6)",
      ],
      borderColor: [
        "rgba(54, 162, 235, 1)",
        "rgba(75, 192, 192, 1)",
        "rgba(153, 102, 255, 1)",
        "rgba(255, 159, 64, 1)",
        "rgba(255, 205, 86, 1)",
      ],
      borderWidth: 1,
    },
  ],
};

// Right Chart Data
const rightChartData = {
  labels: ["Sales", "Rent", "Property Finder", "Bayut", "Website"],
  datasets: [
    {
      label: "Right Chart",
      data: [279, 33, 245, 282, 312],
      backgroundColor: [
        "rgba(255, 99, 132, 0.6)",
        "rgba(54, 162, 235, 0.6)",
        "rgba(255, 206, 86, 0.6)",
        "rgba(75, 192, 192, 0.6)",
        "rgba(153, 102, 255, 0.6)",
      ],
      borderColor: [
        "rgba(255, 99, 132, 1)",
        "rgba(54, 162, 235, 1)",
        "rgba(255, 206, 86, 1)",
        "rgba(75, 192, 192, 1)",
        "rgba(153, 102, 255, 1)",
      ],
      borderWidth: 1,
    },
  ],
};

// Configuring the Charts
const config = {
  type: "doughnut",
  options: {
    responsive: true,
    plugins: {
      legend: {
        display: false,
      },
    },
  },
};

// Render Left Chart
const leftChart = new Chart(document.getElementById("leftChart"), {
  ...config,
  data: leftChartData,
});

// Render Right Chart
const rightChart = new Chart(document.getElementById("rightChart"), {
  ...config,
  data: rightChartData,
});

function deleteSelectedProperties() {
  var checkboxes = document.querySelectorAll(
    'input[name="property_ids[]"]:checked'
  );
  var propertyIds = Array.from(checkboxes).map((checkbox) => checkbox.value);

  if (propertyIds.length === 0) {
    alert("No properties selected");
    return;
  }

  var xhr = new XMLHttpRequest();
  xhr.open("POST", "delete.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    if (xhr.status === 200) {
      console.log("Response:", xhr.responseText);
      location.reload();
    } else {
      console.error("Error:", xhr.statusText);
    }
  };
  xhr.send("property_ids=" + encodeURIComponent(JSON.stringify(propertyIds)));
}

function publishSelectedProperties() {
  var checkboxes = document.querySelectorAll(
    'input[name="property_ids[]"]:checked'
  );
  var propertyIds = Array.from(checkboxes).map((checkbox) => checkbox.value);

  if (propertyIds.length === 0) {
    alert("No properties selected");
    return;
  }

  var url = `xml.php?property_ids=${encodeURIComponent(
    JSON.stringify(propertyIds)
  )}`;
  window.location.href = url;
}

function exportProperties() {
  var checkboxes = document.querySelectorAll(
    'input[name="property_ids[]"]:checked'
  );
  var propertyIds = Array.from(checkboxes).map((checkbox) => checkbox.value);

  if (propertyIds.length === 0) {
    alert("No properties selected");
    return;
  }

  document.getElementById("exportForm").submit();
}

function publishSelectedPropertiesToBayut() {
  var checkboxes = document.querySelectorAll(
    'input[name="property_ids[]"]:checked'
  );
  var propertyIds = Array.from(checkboxes).map((checkbox) => checkbox.value);

  if (propertyIds.length === 0) {
    alert("No properties selected");
    return;
  }

  var url = `xml.php?platform=bayut&property_ids=${encodeURIComponent(
    JSON.stringify(propertyIds)
  )}`;
  window.location.href = url;
}

function publishSelectedPropertiesToDubizzle() {
  var checkboxes = document.querySelectorAll(
    'input[name="property_ids[]"]:checked'
  );
  var propertyIds = Array.from(checkboxes).map((checkbox) => checkbox.value);

  if (propertyIds.length === 0) {
    alert("No properties selected");
    return;
  }

  var url = `xml.php?platform=dubizzle&property_ids=${encodeURIComponent(
    JSON.stringify(propertyIds)
  )}`;
  window.location.href = url;
}

function publishSelectedPropertiesToPF() {
  var checkboxes = document.querySelectorAll(
    'input[name="property_ids[]"]:checked'
  );
  var propertyIds = Array.from(checkboxes).map((checkbox) => checkbox.value);

  if (propertyIds.length === 0) {
    alert("No properties selected");
    return;
  }

  var url = `xml.php?property_ids=${encodeURIComponent(
    JSON.stringify(propertyIds)
  )}`;
  window.location.href = url;
}

function unPublishSelectedProperties() {
  var checkboxes = document.querySelectorAll(
    'input[name="property_ids[]"]:checked'
  );
  var propertyIds = Array.from(checkboxes).map((checkbox) => checkbox.value);

  if (propertyIds.length === 0) {
    alert("No properties selected");
    return;
  }

  var xhr = new XMLHttpRequest();
  xhr.open("POST", "unpublish.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    if (xhr.status === 200) {
      console.log("Response:", xhr.responseText);
      alert("Success: Properties unpublished successfully");
    } else {
      console.error("Error:", xhr.statusText);
    }
  };
  xhr.send("property_ids=" + encodeURIComponent(JSON.stringify(propertyIds)));
}

function transferSelectedPropertiesToAgent() {
  var checkboxes = document.querySelectorAll(
    'input[name="property_ids[]"]:checked'
  );
  var propertyIds = Array.from(checkboxes).map((checkbox) => checkbox.value);

  if (propertyIds.length === 0) {
    alert("No properties selected");
    return;
  }

  var queryParams = new URLSearchParams({
    property_ids: propertyIds.join(","),
  });
  window.location.href = "transfer_agent.php?" + queryParams.toString();
}

function selectAndAddPropertiesToAgentTransfer() {
  var checkboxes = document.querySelectorAll(
    'input[name="property_ids[]"]:checked'
  );
  var propertyIds = Array.from(checkboxes).map((checkbox) => checkbox.value);
  document.getElementById("transferAgentPropertyIds").value =
    propertyIds.join(",");
}

function selectAndAddPropertiesToOwnerTransfer() {
  var checkboxes = document.querySelectorAll(
    'input[name="property_ids[]"]:checked'
  );
  var propertyIds = Array.from(checkboxes).map((checkbox) => checkbox.value);
  document.getElementById("transferOwnerPropertyIds").value =
    propertyIds.join(",");
}

const bedroomsInput = document.getElementById("bedrooms");
bedroomsInput.addEventListener("change", function () {
  const selectedBedrooms = this.value;
  document.getElementById("selectedBedrooms").innerText =
    " (" + selectedBedrooms + ")";
});

const priceInput = document.getElementById("price");
priceInput.addEventListener("change", function () {
  const selectedPrice = this.value;
  document.getElementById("selectedPrice").innerText =
    " (" + selectedPrice + ")";
});

function copyLink(propertyId) {
  var url = `${window.location.origin}/projects/property-listing/view_listing.php?id=${propertyId}`;
  navigator.clipboard
    .writeText(url)
    .then(function () {
      alert("Link copied to clipboard");
    })
    .catch(function (err) {
      console.error("Failed to copy the link: ", err);
    });
}

function toggleCheckboxes(source) {
  const checkboxes = document.querySelectorAll('input[name="property_ids[]"]');
  checkboxes.forEach((checkbox) => {
    checkbox.checked = source.checked;
  });
}

document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.getElementById("sidebar");
  const sidebarToggle = document.getElementById("sidebarToggle");
  const sidebarClose = document.getElementById("sidebarClose");
  const mainContent = document.querySelector(".flex-grow-1");

  sidebarToggle.addEventListener("click", function () {
    sidebar.classList.add("active");
    sidebarToggle.style.left = "270px";
  });

  sidebarClose.addEventListener("click", function () {
    sidebar.classList.remove("active");
    sidebarToggle.style.left = "20px";
  });

  document.addEventListener("click", function (event) {
    const isClickInsideSidebar = sidebar.contains(event.target);
    const isClickOnToggleButton = sidebarToggle.contains(event.target);

    if (
      !isClickInsideSidebar &&
      !isClickOnToggleButton &&
      sidebar.classList.contains("active")
    ) {
      sidebar.classList.remove("active");
      sidebarToggle.style.left = "20px";
    }
  });

  const currentPage = window.location.pathname.split("/").pop();
  const navLinks = document.querySelectorAll("#sidebar .nav-link");
  navLinks.forEach((link) => {
    if (link.getAttribute("href").includes(currentPage)) {
      link.classList.add("active");
    }
  });
});

function onScreenResize() {
  // Get the screen width
  var screenWidth = window.innerWidth;

  // Add or remove the class based on screen size
  if (screenWidth > 768) {
    document.querySelector(".main-content").classList.add("custom-card");
  } else {
    document.querySelector(".main-content").classList.remove("custom-card");
  }

  // Update the class on window resize
  window.addEventListener("resize", function () {
    var screenWidth = window.innerWidth;
    if (screenWidth > 768) {
      document.querySelector(".main-content").classList.add("custom-card");
    } else {
      document.querySelector(".main-content").classList.remove("custom-card");
    }
  });
}

onScreenResize();

// Define an array of amenities
const amenities = [
  {
    id: "gv",
    label: "Golf view",
  },
  {
    id: "cw",
    label: "City view",
  },
  {
    id: "no",
    label: "North orientation",
  },
  {
    id: "so",
    label: "South orientation",
  },
  {
    id: "eo",
    label: "East orientation",
  },
  {
    id: "wo",
    label: "West orientation",
  },
  {
    id: "ns",
    label: "Near school",
  },
  {
    id: "ho",
    label: "Near hospital",
  },
  {
    id: "tr",
    label: "Terrace",
  },
  {
    id: "nm",
    label: "Near mosque",
  },
  {
    id: "sm",
    label: "Near supermarket",
  },
  {
    id: "ml",
    label: "Near mall",
  },
  {
    id: "pt",
    label: "Near public transportation",
  },
  {
    id: "mo",
    label: "Near metro",
  },
  {
    id: "vt",
    label: "Near veterinary",
  },
  {
    id: "bc",
    label: "Beach access",
  },
  {
    id: "pk",
    label: "Public parks",
  },
  {
    id: "rt",
    label: "Near restaurants",
  },
  {
    id: "ng",
    label: "Near Golf",
  },
  {
    id: "ap",
    label: "Near airport",
  },
  {
    id: "cs",
    label: "Concierge Service",
  },
  {
    id: "ss",
    label: "Spa",
  },
  {
    id: "sy",
    label: "Shared Gym",
  },
  {
    id: "ms",
    label: "Maid Service",
  },
  {
    id: "wc",
    label: "Walk-in Closet",
  },
  {
    id: "ht",
    label: "Heating",
  },
  {
    id: "gf",
    label: "Ground floor",
  },
  {
    id: "sv",
    label: "Server room",
  },
  {
    id: "dn",
    label: "Pantry",
  },
  {
    id: "ra",
    label: "Reception area",
  },
  {
    id: "vp",
    label: "Visitors parking",
  },
  {
    id: "op",
    label: "Office partitions",
  },
  {
    id: "sh",
    label: "Core and Shell",
  },
  {
    id: "cd",
    label: "Children daycare",
  },
  {
    id: "cl",
    label: "Cleaning services",
  },
  {
    id: "nh",
    label: "Near Hotel",
  },
  {
    id: "cr",
    label: "Conference room",
  },
  {
    id: "bl",
    label: "View of Landmark",
  },
  {
    id: "pr",
    label: "Children Play Area",
  },
  {
    id: "bh",
    label: "Beach Access",
  },
];

// Function to generate the amenities HTML
function generateAmenities(amenities) {
  return amenities
    .map(
      (amenity) => `
    <div class="col-12 form-check">
      <input type="checkbox" class="form-check-input hidden-checkbox" id="${amenity.id}" name="amenities[]" value="${amenity.label}">
      <label class="form-check-label styled-label" for="${amenity.id}">${amenity.label}</label>
    </div>
  `
    )
    .join("");
}

// Insert the amenities into the container
document.getElementById("amenities-container").innerHTML =
  generateAmenities(amenities);
document.getElementById("saveAmenities").addEventListener("click", function () {
  let selectedAmenities = [];

  // Get all checkboxes
  const checkboxes = document.querySelectorAll(
    '#amenitiesModal input[type="checkbox"]'
  );

  // Loop through checkboxes and find the ones that are checked
  checkboxes.forEach(function (checkbox) {
    if (checkbox.checked) {
      // Get the label text corresponding to the checkbox
      const label = document.querySelector(
        `label[for=${checkbox.id}]`
      ).innerText;
      selectedAmenities.push(label);
    }
  });

  // If amenities are selected, hide the button and show the selected amenities
  if (selectedAmenities.length > 0) {
    // Hide the Update Amenities button
    document.querySelector(
      'button[data-bs-target="#amenitiesModal"]'
    ).style.display = "none";

    // Show the selected amenities
    const selectedAmenitiesDiv = document.getElementById("selectedAmenities");
    selectedAmenitiesDiv.innerHTML = `<p class="h6 mb-2">Selected Amenities:</p><ul class="list-unstyled"></ul>`;
    selectedAmenities.forEach(function (amenity) {
      selectedAmenitiesDiv.querySelector(
        "ul"
      ).innerHTML += `<li>${amenity}</li>`;
    });
  }

  // Close the modal
  const modal = bootstrap.Modal.getInstance(
    document.getElementById("amenitiesModal")
  );
  modal.hide();
});

document
  .getElementById("bayutEnableFull")
  .addEventListener("change", function () {
    if (this.checked) {
      document.getElementById("bayutEnable").checked = true;
      document.getElementById("dubizleEnable").checked = true;
    }
  });

// // JavaScript to handle image preview
// const floorPlanInput = document.getElementById("floorPlan");
// const floorPlanPreview = document.getElementById("floorPlanPreview");
// const selectedFloorPlan = document.getElementById("selectedFloorPlan");

// const photoInput = document.getElementById("photo");
// const photoPreview = document.getElementById("photoPreview");
// const selectedPhoto = document.getElementById("selectedPhoto");

// floorPlanInput.addEventListener("change", function (event) {
//   const file = event.target.files[0];

//   if (file && file.type.startsWith("image/") && file.size <= 2 * 1024 * 1024) {
//     const reader = new FileReader();

//     reader.onload = function (e) {
//       selectedFloorPlan.src = e.target.result; // Set the image src
//       floorPlanPreview.style.display = "block"; // Show the preview
//     };

//     reader.readAsDataURL(file); // Read the file as a data URL
//   } else {
//     floorPlanPreview.style.display = "none"; // Hide the preview if invalid
//     alert("Please select a valid image file (Max size: 2MB)");
//   }
// });

// photoInput.addEventListener("change", function (event) {
//   const file = event.target.files[0];

//   if (file && file.type.startsWith("image/") && file.size <= 2 * 1024 * 1024) {
//     const reader = new FileReader();

//     reader.onload = function (e) {
//       selectedPhoto.src = e.target.result; // Set the image src
//       photoPreview.style.display = "block"; // Show the preview
//     };

//     reader.readAsDataURL(file); // Read the file as a data URL
//   } else {
//     photoPreview.style.display = "none"; // Hide the preview if invalid
//     alert("Please select a valid image file (Max size: 2MB)");
//   }
// });

// const pfLocation = document.getElementById("propertyLocation");

// const pfCity = document.getElementById("propertyCity");
// const pfCommunity = document.getElementById("propertyCommunity");
// const pfSubCommunity = document.getElementById("propertySubCommunity");
// const pfTower = document.getElementById("propertyTower");

// const bayutLocation = document.getElementById("bayutLocation");
// const bayutCity = document.getElementById("bayutCity");
// const bayutCommunity = document.getElementById("bayutCommunity");
// const bayutSubCommunity = document.getElementById("bayutSubCommunity");
// const bayutTower = document.getElementById("bayutTower");

// pfLocation.addEventListener("change", function () {
//   const location = pfLocation.value;

//   pfCity.value = location.split(" - ")[0];
//   pfCommunity.value = location.split(" - ")[1];
//   pfSubCommunity.value = location.split(" - ")[2];
//   pfTower.value = location.split(" - ")[3];
// });

// bayutLocation.addEventListener("change", function () {
//   const location = bayutLocation.value;

//   bayutCity.value = location.split(" - ")[0];
//   bayutCommunity.value = location.split(" - ")[1];
//   bayutSubCommunity.value = location.split(" - ")[2];
//   bayutTower.value = location.split(" - ")[3];
// });

// view_listing
// JavaScript to handle thumbnail click and change the preview image
const carousel = document.querySelector('.carousel');
const prevButton = document.querySelector('.prev-button');
const nextButton = document.querySelector('.next-button');

const totalItems = 10;
const visibleItems = 4;
let currentIndex = 0;

function updateCarousel() {
    carousel.style.transform = `translateX(-${currentIndex * 210}px)`;
}

function moveNext() {
    currentIndex++;
    if (currentIndex >= totalItems) {
        currentIndex = 0;
        carousel.style.transition = 'none';
        updateCarousel();
        setTimeout(() => {
            carousel.style.transition = 'transform 0.5s ease';
        }, 10);
    } else {
        updateCarousel();
    }
}

function movePrev() {
    currentIndex--;
    if (currentIndex < 0) {
        currentIndex = totalItems - 1;
        carousel.style.transition = 'none';
        updateCarousel();
        setTimeout(() => {
            carousel.style.transition = 'transform 0.5s ease';
        }, 10);
    } else {
        updateCarousel();
    }
}

nextButton.addEventListener('click', moveNext);
prevButton.addEventListener('click', movePrev);

// Clone necessary items for seamless looping
const itemWidth = 210; // 200px width + 10px margin
const cloneCount = Math.ceil(carousel.offsetWidth / itemWidth);

for (let i = 0; i < cloneCount; i++) {
    const clone = carousel.children[i].cloneNode(true);
    carousel.appendChild(clone);
}

// Initial position
updateCarousel();

//update the thumbnails on click
const thumbnails = document.querySelectorAll('.thumbnail');
const previewImage = document.getElementById('previewImage');

thumbnails.forEach(thumbnail => {
    thumbnail.addEventListener('click', function() {
        previewImage.src = this.getAttribute('data-src');

        thumbnails.forEach(img => img.classList.remove('active'));
        this.classList.add('active');
    });
});