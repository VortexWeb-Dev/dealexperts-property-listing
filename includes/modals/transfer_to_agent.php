<!-- Modal (Transfer to Agent) -->
<div class="modal fade" id="transferAgentModal" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transferModalLabel">Transfer Property to Agent</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form inside modal -->
                <form id="transferAgentForm" method="POST" action="transfer_agent.php" onsubmit="handleTransferAgentSubmit(event)">
                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                    <input type="hidden" id="transferAgentPropertyIds" name="transferAgentPropertyIds">

                    <div class="form-group">
                        <label for="agentSelect">Select Listing Agent</label>
                        <select class="form-control" id="agentSelect" name="agent_id" required>
                            <option value="">-- Select Agent --</option>
                            <?php
                            // Fetch and display listing agents
                            $agents_result = CRest::call('crm.item.list', ['entityTypeId' => LISTING_AGENTS_ENTITY_TYPE_ID]);
                            $listing_agents = $agents_result['result']['items'] ?? [];

                            if (empty($listing_agents)) {
                                echo '<option disabled>No agents found</option>';
                            } else {
                                foreach ($listing_agents as $agent) {
                                    echo '<option value="' . htmlspecialchars($agent['id']) . '">' . htmlspecialchars($agent['ufCrm46AgentName']) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Submit Button with Loading Indicator -->
                    <button type="submit" class="btn btn-primary" id="transferAgentBtn">
                        Transfer <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>