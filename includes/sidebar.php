<?php
$currentPage = basename($_SERVER['PHP_SELF']); // Get the current page filename
?>


<style>
	@media (max-width: 767.98px) {
		#sidebar {
			position: fixed;
			left: -250px;
			transition: left 0.3s ease-in-out;
			z-index: 1040;
		}

		#sidebar.active {
			left: 0;
		}

		.main-content {
			margin-left: 0 !important;
			transition: margin-left 0.3s ease-in-out;
		}

		#sidebarToggle {
			display: flex;
			align-items: center;
			justify-content: center;
		}

		#sidebar.active~#sidebarToggle {
			left: 270px;
		}

		#sidebarClose {
			display: none;
		}

		#sidebar.active #sidebarClose {
			display: block;
		}
	}

	@media (min-width: 768px) {

		#sidebarToggle,
		#sidebarClose {
			display: none;
		}
	}

	#sidebar .nav-link {
		color: #64748b;
		font-weight: 400;
		transition: background-color 0.3s, color 0.3s;
	}

	#sidebar .nav-link:hover,
	#sidebar .nav-link.active {
		background-color: #f8f9fa;
		color: #334155;
		font-weight: 600;
	}

	#sidebar .nav-link i {
		width: 20px;
		text-align: center;
	}
</style>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		const sidebar = document.getElementById('sidebar');
		const sidebarToggle = document.getElementById('sidebarToggle');
		const sidebarClose = document.getElementById('sidebarClose');
		const mainContent = document.querySelector('.flex-grow-1');

		sidebarToggle.addEventListener('click', function() {
			sidebar.classList.add('active');
			// sidebarToggle.style.left = '270px';
			sidebarToggle.style.display = 'none';
		});

		sidebarClose.addEventListener('click', function() {
			sidebar.classList.remove('active');
			sidebarToggle.style.display = 'flex';
			sidebarToggle.style.left = '20px';
		});

		// Close sidebar when clicking outside of it
		document.addEventListener('click', function(event) {
			const isClickInsideSidebar = sidebar.contains(event.target);
			const isClickOnToggleButton = sidebarToggle.contains(event.target);

			if (!isClickInsideSidebar && !isClickOnToggleButton && sidebar.classList.contains('active')) {
				sidebar.classList.remove('active');
				sidebarToggle.style.left = '20px';
			}
		});
	});
</script>

<!-- Sidebar -->

<nav id="sidebar" class="bg-white sidebar sticky-top shadow-sm" style="width: 230px; height: 100vh; overflow-y: auto; border-right: 1px solid #ddd;">
	<div class="position-sticky">
		<div class="ps-4 d-flex justify-content-between align-items-center border-bottom" style="height: 60px;">
			<div>
				<h4 class="mb-0 fw-bold text-primary" style="font-size: 1.5rem;">Property Listing</h4>
				<p class="text-small text-muted mb-0 text-center">DealsExperts</p>
			</div>
			<button id="sidebarClose" class="btn btn-link d-md-none text-dark">
				<i class="fa-solid fa-times"></i>
			</button>
		</div>
		<ul class="nav flex-column py-3">
			<li class="nav-item">
				<a class="nav-link py-3 px-4 d-flex align-items-center <?= ($currentPage === 'index.php') ? 'active' : '' ?>" href="./index.php">
					<i class="fa-solid fa-home me-3"></i>
					<span>Property Listing</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link py-3 px-4 d-flex align-items-center <?= ($currentPage === 'listing_agents.php') ? 'active' : '' ?>" href="./listing_agents.php">
					<i class="fa-solid fa-user-group me-3"></i>
					<span>Listing Agents</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link py-3 px-4 d-flex align-items-center <?= ($currentPage === 'locations.php') ? 'active' : '' ?>" href="./locations.php">
					<i class="fa-regular fa-map me-3"></i>
					<span>Locations</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link py-3 px-4 d-flex align-items-center <?= ($currentPage === 'bayut_locations.php') ? 'active' : '' ?>" href="./bayut_locations.php">
					<i class="fa-solid fa-map-pin me-3"></i>
					<span>Bayut Locations</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link py-3 px-4 d-flex align-items-center <?= ($currentPage === 'landlords.php') ? 'active' : '' ?>" href="./landlords.php">
					<i class="fa-solid fa-house-user me-3"></i>
					<span>Landlords</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link py-3 px-4 d-flex align-items-center <?= ($currentPage === 'developers.php') ? 'active' : '' ?>" href="./developers.php">
					<i class="fa-solid fa-helmet-safety me-3"></i>
					<span>Developers</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link py-3 px-4 d-flex align-items-center <?= ($currentPage === 'settings.php') ? 'active' : '' ?>" href="./settings.php">
					<i class="fa-solid fa-gear me-3"></i>
					<span>General Settings</span>
				</a>
			</li>
		</ul>
	</div>
</nav>