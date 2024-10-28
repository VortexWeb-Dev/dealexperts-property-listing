<?php
require_once __DIR__ . '/crest/crest.php';
require_once __DIR__ . '/crest/settings.php';

function fetchProperties($filter = null)
{
    $params = [
        'entityTypeId' => PROPERTY_LISTING_ENTITY_TYPE_ID,
    ];

    if ($filter) {
        $params['filter'] = $filter;
    }

    $response = CRest::call('crm.item.list', $params);

    return $response['result']['items'] ?? [];
}

// Check if a filter is set in the URL and fetch properties
$filter = $_GET['filter'] ?? null;
$properties = fetchProperties($filter);

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

        .dropzone {
            cursor: pointer;
            background-color: #f8f9fa;
            /* Light background color */
            transition: background-color 0.3s, border-color 0.3s;
        }

        .dropzone:hover {
            background-color: #e9ecef;
            /* Slightly darker on hover */
            border-color: #007bff;
            /* Blue border on hover */
        }

        .dropzone:after {
            content: '\f093';
            /* Font Awesome upload icon */
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            color: #007bff;
            font-size: 2rem;
            display: block;
            margin-bottom: 1rem;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">

</head>

<body>
    <div class="d-flex">

        <!-- Sticky Left Sidebar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="flex-grow-1" style="height: 100vh; overflow-y: auto;">
            <!-- Fixed Topbar -->
            <?php include 'includes/topbar.php'; ?>
            <div class="container-fluid py-4">
                <div class="container mt-5">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <ul class="nav nav-pills gap-1 flex-grow-1">
                            <li class="nav-item">
                                <a class="nav-link completed" href="create_listing.php">Property Details</a>
                            </li>
                            <li class="d-flex justify-content-center align-items-center mx-2">
                                <i class="fa-solid fa-chevron-right"></i>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link completed" href="notes.php">Notes</a>
                            </li>
                            <li class="d-flex justify-content-center align-items-center mx-2">
                                <i class="fa-solid fa-chevron-right"></i>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link completed" href="documents.php">Documents</a>
                            </li>
                            <li class="d-flex justify-content-center align-items-center mx-2">
                                <i class="fa-solid fa-chevron-right"></i>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link completed" href="approval.php">Approval</a>
                            </li>
                            <li class="d-flex justify-content-center align-items-center mx-2">
                                <i class="fa-solid fa-chevron-right"></i>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="completed.php">Completed</a>
                            </li>
                        </ul>
                        <button class="btn btn-success mx-3">
                            <i class="fas fa-save"></i> Save
                        </button>
                    </div>
                </div>

                <div class="container mt-5">
                    <div class="row p-3 bg-light rounded border">
                        <h4>Publishing Status</h4>
                        <p>Below, you will find a switch button that controls the publishing status for the current property. Please use it to manage the property's visibility.</p>

                        <div class="form-group">
                            <div class="custom-control custom-radio">
                                <input type="radio" id="publish" name="publishStatus" class="custom-control-input" checked>
                                <label class="custom-control-label" for="publish">Publish</label>
                            </div>

                            <div class="custom-control custom-radio">
                                <input type="radio" id="saveAsDraft" name="publishStatus" class="custom-control-input">
                                <label class="custom-control-label" for="saveAsDraft">Save as draft</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
                    <a href="approval.php" class="btn btn-outline-primary">
                        <i class="fa fa-arrow-left"></i> Previous
                    </a>
                    <div class="d-flex gap-3">
                        <button type="button" class="btn btn-success">
                            <i class="fa fa-save"></i> Save
                        </button>
                        <a href="#" class="btn btn-primary">
                            <i class="fa fa-paper-plane"></i> Submit
                        </a>
                    </div>
                </div>
            </div> 
            <!-- Main content area ends -->
        </div>
    </div>


    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="./js/script.js"></script>
</body>

</html>