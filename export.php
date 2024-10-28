<?php
require_once(__DIR__ . '/crest/crest.php');
require_once(__DIR__ . '/crest/settings.php');

// Function to fetch property details by ID from the SPA
function fetchPropertyDetails($id)
{
    $response = CRest::call('crm.item.get', [
        'entityTypeId' => PROPERTY_LISTING_ENTITY_TYPE_ID,
        'id' => $id
    ]);

    return $response['result']['item'] ?? [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['property_ids']) && !empty($_POST['property_ids'])) {
        $propertyIds = $_POST['property_ids'];
        // $portal = $_POST['portal'];

        // Start output buffering
        ob_start();

        // Fetch property details
        $properties = [];
        foreach ($propertyIds as $id) {
            try {
                $property = fetchPropertyDetails($id);
                if ($property) {
                    $properties[] = $property;
                }
            } catch (Exception $e) {
                echo "Error: " . htmlspecialchars($e->getMessage()) . "<br>";
            }
        }

        // Generate XML
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><list/>');
        $xml->addAttribute('last_update', date('y-m-d H:i:s')); // Current date-time
        $xml->addAttribute('listing_count', count($properties));

        foreach ($properties as $property) {
            $propertyNode = $xml->addChild('property');
            $propertyNode->addAttribute('last_update', date('y-m-d H:i:s', strtotime($property['updatedTime'] ?? '')));
            $propertyNode->addAttribute('id', $property['id'] ?? '');

            addCDataElement($propertyNode, 'reference_number', $property['ufCrm42ReferenceNumber'] ?? '');
            addCDataElement($propertyNode, 'permit_number', $property['ufCrm42PermitNumber'] ?? '');

            if (isset($property['ufCrm42RentalPeriod']) && $property['ufCrm42RentalPeriod'] === 'M') {
                addCDataElement($propertyNode->addChild('price'), 'monthly', $property['ufCrm42Price'] ?? '');
            }

            addCDataElement($propertyNode, 'offering_type', $property['ufCrm42OfferingType'] ?? '');
            addCDataElement($propertyNode, 'property_type', $property['ufCrm42PropertyType'] ?? '');

            addCDataElement($propertyNode, 'geopoints', $property['ufCrm42Geopoints'] ?? '');
            addCDataElement($propertyNode, 'city', $property['ufCrm42City'] ?? '');
            addCDataElement($propertyNode, 'community', $property['ufCrm42Community'] ?? '');
            addCDataElement($propertyNode, 'sub_community', $property['ufCrm42SubCommunity'] ?? '');
            addCDataElement($propertyNode, 'title_en', $property['ufCrm42TitleEn'] ?? '');
            addCDataElement($propertyNode, 'description_en', $property['ufCrm42DescriptionEn'] ?? '');
            addCDataElement($propertyNode, 'size', $property['ufCrm42Size'] ?? '');
            addCDataElement($propertyNode, 'bedroom', $property['ufCrm42Bedroom'] ?? '');
            addCDataElement($propertyNode, 'bathroom', $property['ufCrm42Bathroom'] ?? '');

            $agentNode = $propertyNode->addChild('agent');
            addCDataElement($agentNode, 'id', $property['ufCrm42AgentId'] ?? '');
            addCDataElement($agentNode, 'name', $property['ufCrm42AgentName'] ?? '');
            addCDataElement($agentNode, 'email', $property['ufCrm42AgentEmail'] ?? '');
            addCDataElement($agentNode, 'phone', $property['ufCrm42AgentPhone'] ?? '');
            addCDataElement($agentNode, 'photo', $property['ufCrm42AgentPhoto'] ?? '');

            $photoNode = $propertyNode->addChild('photo');
            foreach ($property['ufCrm42Photos'] as $photo) {

                $urlNode = addCDataElement($photoNode, 'url', $photo);
                $urlNode->addAttribute('last_update', date('Y-m-d H:i:s'));
                $urlNode->addAttribute('watermark', 'Yes');
            }

            addCDataElement($propertyNode, 'parking', $property['ufCrm42Parking'] ?? '');
            addCDataElement($propertyNode, 'furnished', $property['ufCrm42Furnished'] ?? '');
            addCDataElement($propertyNode, 'price_on_application', $property['ufCrm42PriceOnApplication'] ?? '');
        }

        // End output buffering and get content
        $content = ob_get_clean();
        $fileName = 'test' . '_properties_' . date('y-m-d_H-i-s') . '.xml';

        header('Content-Type: application/xml');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        echo $xml->asXML();
        exit;
    } else {
        echo "No properties selected or portal not specified.";
    }
} else {
    echo "Invalid request method.";
}

// Helper function to add CDATA
function addCDataElement(SimpleXMLElement $node, $name, $value)
{
    $child = $node->addChild($name);
    $dom = dom_import_simplexml($child);
    $dom->appendChild($dom->ownerDocument->createCDATASection($value));

    return $child;
}
