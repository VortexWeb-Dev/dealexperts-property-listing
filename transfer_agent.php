<?php
require_once(__DIR__ . '/crest/crest.php');
require_once(__DIR__ . '/crest/settings.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $propertyIdsString = isset($_POST['transferAgentPropertyIds']) ? $_POST['transferAgentPropertyIds'] : '';
    $agent_id = isset($_POST['agent_id']) ? $_POST['agent_id'] : '';

    $propertyIds = explode(',', $propertyIdsString);

    if (!empty($propertyIds)) {
        $agent_res = CRest::call('crm.item.get', [
            'entityTypeId' => LISTING_AGENTS_ENTITY_TYPE_ID,
            'id' => $agent_id
        ]);
        $agent = $agent_res['result']['item'] ?? null;

        if (!$agent) {
            header('Location: index.php');
            exit;
        }
        foreach ($propertyIds as $propertyId) {
            $res = CRest::call('crm.item.update', [
                'entityTypeId' => PROPERTY_LISTING_ENTITY_TYPE_ID,
                'id' => $propertyId,
                'fields' => [
                    'ufCrm42AgentId' => $agent_id,
                    'ufCrm42AgentName' => $agent['ufCrm46AgentName'],
                    'ufCrm42AgentEmail' => $agent['ufCrm46AgentEmail'],
                    'ufCrm46AgentMobile' => $agent['ufCrm46AgentPhone'],
                ]
            ]);
        }

        header('Location: index.php');
    } else {
        header('Location: index.php');
    }
} else {
    echo 'Invalid request method.';
}
