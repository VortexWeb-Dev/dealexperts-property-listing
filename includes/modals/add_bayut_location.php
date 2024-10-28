<!-- Modal for Adding Location -->
<div class="modal fade" id="addLocationModal" tabindex="-1" aria-labelledby="addLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLocationModalLabel">Add Location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addLocationForm" method="post" action="./bayut_locations.php">
                    <div class="mb-3">
                        <label for="locationInput" class="form-label">Location</label>
                        <input type="text" class="form-control" id="locationInput" name="location" placeholder="City - Community - Sub Community - Building" required>
                    </div>
                </form>
                <p>Enter the location in the following format:<br>"City - Community - Sub Community - Building"</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="addLocationForm">Add Location</button>
            </div>
        </div>
    </div>
</div>