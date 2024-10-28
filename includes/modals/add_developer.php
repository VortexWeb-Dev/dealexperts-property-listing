<div class="modal fade" id="addDeveloperModal" tabindex="-1" aria-labelledby="addDeveloperModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDeveloperModalLabel">Add Developer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addDeveloperForm" method="post" action="./developers.php">
                    <div class="mb-3">
                        <label for="developer" class="form-label">Developer Name</label>
                        <input type="text" class="form-control" id="developer" name="developer" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="addDeveloperForm">Add Developer</button>
            </div>
        </div>
    </div>
</div>