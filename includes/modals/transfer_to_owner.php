<!-- Modal (Transfer to Owner) -->
<div class="modal fade" id="transferOwnerModal" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transferModalLabel">Transfer Property to Owner</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form inside modal -->
                <form id="transferOwnerForm" method="POST" action="transfer_owner.php">
                    <input type="hidden" id="transferOwnerPropertyIds" name="transferOwnerPropertyIds">
                    <div class="form-group">
                        <label for="ownerSelect">Select Listing Owner</label>
                        <select class="form-control" id="ownerSelect" name="owner_id" required>
                            <?php
                            // Fetch and display listing owners
                            $owners_result = CRest::call('crm.item.list', ['entityTypeId' => LANDLORDS_ENTITY_TYPE_ID]);
                            $listing_owners = $owners_result['result']['items'] ?? [];

                            foreach ($listing_owners as $owner) {
                                echo '<option value="' . htmlspecialchars($owner['id']) . '">' . htmlspecialchars($owner['ufCrm50LandlordName']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Transfer</button>
                </form>
            </div>
        </div>
    </div>
</div>