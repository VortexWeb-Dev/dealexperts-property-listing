<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <link rel="stylesheet" href="/styles/app.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .btn-custom {
            background-color: #007bff;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.25rem;
            font-size: 1.1rem;
            color: #fff;
            transition: background-color 0.3s ease;
        }

        .btn-custom:hover {
            color: #fff;
            background-color: #0056b3;
        }

        input[type="file"] {
            padding: 0.5rem;
            border-radius: 0.5rem;
            border: 1px solid #ced4da;
        }

        .watermark-card {
            background-color: #fff;
            border-radius: 0.75rem;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .watermark-card .card-body {
            padding: 1.5rem;
        }

        label {
            font-weight: 600;
        }

        /* Customize the file upload button */
        .file-upload-wrapper {
            position: relative;
        }

        .file-upload {
            width: 100%;
            height: 50px;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            border-radius: 0.5rem;
        }

        .file-upload input[type="file"] {
            position: absolute;
            font-size: 100px;
            opacity: 0;
            right: 0;
            top: 0;
        }

        .file-upload label {
            padding: 15px;
            background-color: #007bff;
            color: #fff;
            text-align: center;
            cursor: pointer;
            display: block;
            transition: all 0.3s;
        }

        .file-upload label:hover {
            background-color: #0056b3;
        }

        .property-wrapper {
            position: relative;
            width: 100%;
            max-width: 400px;
            /* Adjust this as needed */
        }

        .property-image {
            width: 100%;
            height: auto;
            border-radius: 0.75rem;
        }

        .watermark-overlay {
            position: absolute;
            bottom: 0px;
            right: 0px;
            opacity: 0.3;
            width: 100%;
            /* Adjust watermark size */
            height: 100%;
            z-index: 2;
        }

        @media (max-width: 768px) {
            .custom-card {
                margin-left: 0 !important;
            }
        }

        @media (max-width: 767.98px) {
            .custom-card {
                margin-left: 0 !important;
                transition: margin-left 0.3s ease-in-out;
            }

            #sidebar {
                position: fixed;
                left: -250px;
                transition: left 0.3s ease-in-out;
                z-index: 1040;
            }

            #sidebar.active {
                left: 0;
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
            color: #333;
            transition: background-color 0.3s, color 0.3s;
        }

        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            background-color: #f8f9fa;
            color: #007bff;
        }

        #sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }

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
        /* input[type="radio"] {
			display: none;
		} */

        /* Label styling for unselected state */
        /* label {
            cursor: pointer;
            border: 2px solid transparent;
            padding: 10px;
            border-radius: 10px;
        } */

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

        h2.display-10 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .property-tag {
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
            margin-right: 0.5rem;
            border-radius: 1rem;
            background-color: #007bff;
            color: white;
        }

        .property-tag.bg-secondary {
            background-color: #6c757d;
        }

        .text-muted {
            color: #6c757d !important;
            font-size: 0.9rem;
        }

        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background-color: transparent;
            border-bottom: none;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #495057;
        }

        .card-body {
            padding: 1rem;
        }

        table.table-sm {
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        table.table-sm td {
            padding: 0.5rem 0;
            color: #495057;
        }

        .btn-primary,
        .badge.bg-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .listing-images img {
            border-radius: 0.75rem;
            transition: transform 0.3s ease;
        }

        .listing-images img:hover {
            transform: scale(1.05);
        }

        .amenity-badge {
            border: 1px solid #007bff;
            border-radius: 20px;
            padding: 0.5rem 1rem;
            margin: 0.25rem;
            display: inline-block;
            font-size: 0.85rem;
            background-color: rgba(0, 123, 255, 0.1);
        }

        .section-bg {
            padding: 2rem 0;
            margin-bottom: 1rem;
            background-color: #f1f1f1;
            border-radius: 0.5rem;
        }

        h3.h3 {
            font-size: 1.5rem;
            font-weight: 600;
        }

        /* image gallery  */
        .gallery-preview img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }

        .carousel-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            overflow: hidden;
            position: relative;
        }

        .carousel {
            display: flex;
            transition: transform 0.5s ease;
        }

        .carousel-item {
            flex: 0 0 200px;
            height: 150px;
            margin-right: 10px;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #333;
        }

        .carousel-item img {
            height: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.3s ease;
            border-radius: 5px;
        }

        .carousel-item img.active {
            opacity: 1;
            border: 2px solid #007bff;
        }

        .prev-button,
        .next-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }

        .prev-button {
            left: 10px;
        }

        .next-button {
            right: 10px;
        }

        /* Mobile responsiveness improvements */
        @media (max-width: 768px) {
            .text-md-end {
                text-align: left !important;
            }

            .card-body h5.card-title {
                font-size: 1rem;
            }

            .listing-images img {
                height: 150px;
            }
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <?php include 'includes/sidebar.php'; ?>