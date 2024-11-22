<div class="accordion mb-4" id="accordionExample">
    <!-- Charts -->
    <div class="accordion-item border-0 shadow-sm mb-3">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                <i class="fas fa-chart-pie me-2 text-primary"></i>Listing Infographics
            </button>
        </h2>

        <?php

        // Initialize counts
        $residentialSales = $residentialRent = $residentialFinder = $residentialBayut = $residentialWebsite = 0;
        $commercialSales = $commercialRent = $commercialFinder = $commercialBayut = $commercialWebsite = 0;

        // Iterate through properties to categorize and count
        foreach ($properties as $property) {
            if ($property['ufCrm42PropertyType'] === 'residential') {
                // Count Residential properties by source
                $residentialSales += ($property['sourceId'] === 'CALL') ? 1 : 0;
                $residentialRent += ($property['sourceId'] === 'RENT') ? 1 : 0;
                $residentialFinder += ($property['ufCrm42PfEnable'] === 'Y') ? 1 : 0;
                $residentialBayut += ($property['ufCrm42BayutEnable'] === 'Y') ? 1 : 0;
                $residentialWebsite += ($property['ufCrm42WebsiteEnable'] === 'Y') ? 1 : 0;
            } elseif ($property['ufCrm42PropertyType'] === 'commercial') {
                // Count Commercial properties by source
                $commercialSales += ($property['sourceId'] === 'CALL') ? 1 : 0;
                $commercialRent += ($property['sourceId'] === 'RENT') ? 1 : 0;
                $commercialFinder += ($property['ufCrm42PfEnable'] === 'Y') ? 1 : 0;
                $commercialBayut += ($property['ufCrm42BayutEnable'] === 'Y') ? 1 : 0;
                $commercialWebsite += ($property['ufCrm42WebsiteEnable'] === 'Y') ? 1 : 0;
            }
        }
        ?>

        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
            <div class="accordion-body bg-white">
                <div class="row">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <h3 class="text-center mb-4 text-primary">Residential</h3>
                        <div class="d-flex justify-content-center">
                            <canvas id="leftChart" width="250" height="250"></canvas>
                            <div class="ms-4 d-flex align-items-center">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><span class="badge rounded-pill" style="background-color: rgba(54, 162, 235, 0.6);"><?= $residentialSales ?></span> Sales</li>
                                    <li class="mb-2"><span class="badge rounded-pill" style="background-color: rgba(75, 192, 192, 0.6);"><?= $residentialRent ?></span> Rent</li>
                                    <li class="mb-2"><span class="badge rounded-pill" style="background-color: rgba(153, 102, 255, 0.6);"><?= $residentialFinder ?></span> Property Finder</li>
                                    <li class="mb-2"><span class="badge rounded-pill" style="background-color: rgba(255, 159, 64, 0.6);"><?= $residentialBayut ?></span> Bayut</li>
                                    <li class="mb-2"><span class="badge rounded-pill" style="background-color: rgba(255, 205, 86, 0.6);"><?= $residentialWebsite ?></span> Website</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h3 class="text-center mb-4 text-primary">Commercial</h3>
                        <div class="d-flex justify-content-center">
                            <canvas id="rightChart" width="250" height="250"></canvas>
                            <div class="ms-4 d-flex align-items-center">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><span class="badge rounded-pill" style="background-color: rgba(255, 99, 132, 0.6);"><?= $commercialSales ?></span> Sales</li>
                                    <li class="mb-2"><span class="badge rounded-pill" style="background-color: rgba(54, 162, 235, 0.6);"><?= $commercialRent ?></span> Rent</li>
                                    <li class="mb-2"><span class="badge rounded-pill" style="background-color: rgba(255, 206, 86, 0.6);"><?= $commercialFinder ?></span> Property Finder</li>
                                    <li class="mb-2"><span class="badge rounded-pill" style="background-color: rgba(75, 192, 192, 0.6);"><?= $commercialBayut ?></span> Bayut</li>
                                    <li class="mb-2"><span class="badge rounded-pill" style="background-color: rgba(153, 102, 255, 0.6);"><?= $commercialWebsite ?></span> Website</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- filter  -->
    <div class="accordion-item border-0 shadow-sm">
        <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                <i class="fas fa-filter me-2 text-primary"></i>Filters
            </button>
        </h2>

        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
            <div class="accordion-body bg-white">
                <div class="modal-content">

                    <?php
                    $locations_res = CRest::call('crm.item.list', ['entityTypeId' => LOCATIONS_ENTITY_TYPE_ID]);
                    $locations = $locations_res['result']['items'] ?? [];

                    $city_res = CRest::call('crm.item.list', ['entityTypeId' => CITIES_ENTITY_TYPE_ID]);
                    $cities = $city_res['result']['items'] ?? [];
                    $cityNames = [];
                    foreach ($cities as $key => $city) {
                       array_push($cityNames, $city['ufCrm56City']);
                    }
                    $cityNames = array_unique($cityNames, SORT_STRING);

                    $communities_res = CRest::call('crm.item.list', ['entityTypeId' => COMMUNITIES_ENTITY_TYPE_ID]);
                    $communities = $communities_res['result']['items'] ?? [];
        
                    $sub_communities_res = CRest::call('crm.item.list', ['entityTypeId' => SUB_COMMUNITIES_ENTITY_TYPE_ID]);
                    $sub_communities = $sub_communities_res['result']['items'] ?? [];

                    $buildings_res = CRest::call('crm.item.list', ['entityTypeId' => BUILDINGS_ENTITY_TYPE_ID]);
                    $buildings = $buildings_res['result']['items'] ?? [];

                    $developers_res = CRest::call('crm.item.list', ['entityTypeId' => DEVELOPERS_ENTITY_TYPE_ID]);
                    $developers = $developers_res['result']['items'] ?? [];

                    $agents_res = CRest::call('crm.item.list', ['entityTypeId' => LISTING_AGENTS_ENTITY_TYPE_ID]);
                    $agents = $agents_res['result']['items'] ?? [];

                    $landlords_res = CRest::call('crm.item.list', ['entityTypeId' => LANDLORDS_ENTITY_TYPE_ID]);
                    $landlords = $landlords_res['result']['items'] ?? [];

                    $property_types = array(
                        "AP" => "Apartment / Flat",
                        "BW" => "Bungalow",
                        "CD" => "Compound",
                        "DX" => "Duplex",
                        "FF" => "Full floor",
                        "HF" => "Half floor",
                        "LP" => "Land / Plot",
                        "PH" => "Penthouse",
                        "TH" => "Townhouse",
                        "VH" => "Villa / House",
                        "WB" => "Whole Building",
                        "HA" => "Short Term / Hotel Apartment",
                        "LC" => "Labor camp",
                        "BU" => "Bulk units",
                        "WH" => "Warehouse",
                        "FA" => "Factory",
                        "OF" => "Office space",
                        "RE" => "Retail",
                        "LP" => "Plot",
                        "SH" => "Shop",
                        "SR" => "Show Room",
                        "SA" => "Staff Accommodation"
                    );

                    ?>
                    <div class="modal-body">
                        <form id="filterForm" method="GET" action="index.php" onsubmit="return prepareFilters();">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="refId" class="form-label">Ref. ID</label>
                                    <input type="text" id="refId" name="refId" class="form-control" value="">
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="city" class="form-label">City</label>
                                    <select id="city" name="city" class="form-select">
                                        <option value="">Select City</option>
                                        <?php
                                        foreach ($cityNames as $city) {
                                            // echo '<option value="' . $city['ufCrm56City'] . '">' . $city['ufCrm56City'] . '</option>';
                                            echo '<option value="' . $city . '">' . $city . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <!-- fetch conditional communities -->
                                <script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        const cityContainer = document.getElementById('city');
                                        const communityContainer = document.getElementById('community');

                                        cityContainer.addEventListener('change', function() {
                                            const selectedCity = cityContainer.value;
                                            fetchCommunityDetails(selectedCity);
                                        });

                                        function fetchCommunityDetails(selectedCity) {
                                            if (selectedCity) {
                                                console.log('Selected City:', selectedCity);

                                                const webhookUrl = 'https://dealexpertsrealestate.bitrix24.com/rest/76/wbocw4wnp63fyits/crm.item.list';

                                                const data = {
                                                    "entityTypeId": <?= LOCATIONS_ENTITY_TYPE_ID ?>,
                                                    "select": ["id", "ufCrm48Location"],
                                                    "filter": {
                                                        "%ufCrm48Location": selectedCity
                                                    }

                                                };
                                                fetch(webhookUrl, {
                                                        method: 'POST',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'Accept': 'application/json'
                                                        },
                                                        body: JSON.stringify(data)
                                                    })
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        console.log('Community Details:', data);
                                                        const communities = data.result.items.map(item => {
                                                            const location = item.ufCrm48Location.split(' - ');
                                                            return {
                                                                id: item.id,
                                                                community: location[1] ?? null
                                                            }
                                                        });
                                                        const communities_set = new Set(communities.map(community => community.community));
                                                        communityContainer.innerHTML = '';
                                                        communityContainer.appendChild(new Option('Select Community', ''));
                                                        Array.from(communities_set).forEach(community => {
                                                            const option = document.createElement('option');
                                                            option.value = community;
                                                            option.text = community;
                                                            communityContainer.appendChild(option);
                                                        });

                                                    })
                                                    .catch(error => {
                                                        console.error('Error fetching community details:', error);
                                                    });
                                            }
                                        }
                                    });
                                </script>
                                <div class="col-md-3">
                                    <label for="community" class="form-label">Community</label>
                                    <select id="community" name="community" class="form-select">
                                        <option value="">Select Community</option>
                                        <!-- <?php
                                        foreach ($communities as $community) {
                                            echo '<option value="' . $community['ufCrm58Community'] . '">' . $community['ufCrm58Community'] . '</option>';
                                        }
                                        ?> -->
                                    </select>
                                </div>
                                <!-- fetch conditional subCommunities -->
                                <script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        const communityContainer = document.getElementById('community');
                                        const subCommunityContainer = document.getElementById('subCommunity');

                                        communityContainer.addEventListener('change', function() {
                                            const selectedCommunity = communityContainer.value;
                                            fetchCommunityDetails(selectedCommunity);
                                        });

                                        function fetchCommunityDetails(selectedCommunity) {
                                            if (selectedCommunity) {
                                                console.log('Selected Community:', selectedCommunity);

                                                const webhookUrl = 'https://dealexpertsrealestate.bitrix24.com/rest/76/wbocw4wnp63fyits/crm.item.list';

                                                const data = {
                                                    "entityTypeId": <?= LOCATIONS_ENTITY_TYPE_ID ?>,
                                                    "select": ["id", "ufCrm48Location"],
                                                    "filter": {
                                                        "%ufCrm48Location": selectedCommunity
                                                    }

                                                };
                                                fetch(webhookUrl, {
                                                        method: 'POST',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'Accept': 'application/json'
                                                        },
                                                        body: JSON.stringify(data)
                                                    })
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        console.log('Community Details:', data);
                                                        const sub_communities = data.result.items.map(item => {
                                                            const location = item.ufCrm48Location.split(' - ');
                                                            return {
                                                                id: item.id,
                                                                subCommunity: location[2] ?? null
                                                            }
                                                        });
                                                        const sub_communities_set = new Set(sub_communities.map(sub_community => sub_community.subCommunity));
                                                        subCommunityContainer.innerHTML = '';
                                                        subCommunityContainer.appendChild(new Option('Select Sub Community', ''));
                                                        Array.from(sub_communities_set).forEach(sub_community => {
                                                            const option = document.createElement('option');
                                                            option.value = sub_community;
                                                            option.text = sub_community;
                                                            subCommunityContainer.appendChild(option);
                                                        });

                                                    })
                                                    .catch(error => {
                                                        console.error('Error fetching community details:', error);
                                                    });
                                            }
                                        }
                                    });
                                </script>
                                <div class="col-md-3">
                                    <label for="subCommunity" class="form-label">Sub Community</label>
                                    <!-- <input type="text" id="subCommunity" name="subCommunity" class="form-control" value=""> -->
                                    <select id="subCommunity" name="subCommunity" class="form-select">
                                        <option value="">Select Sub Community</option>

                                    </select>
                                </div>
                                <!-- fetch conditional buildings -->
                                <script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        const SubCommunityContainer = document.getElementById('subCommunity');
                                        const BuildingContainer = document.getElementById('building');

                                        SubCommunityContainer.addEventListener('change', function() {
                                            const selectedSubCommunity = SubCommunityContainer.value;
                                            fetchCommunityDetails(selectedSubCommunity);
                                        });

                                        function fetchCommunityDetails(selectedSubCommunity) {
                                            if (selectedSubCommunity) {
                                                console.log('Selected SubCommunity:', selectedSubCommunity);

                                                const webhookUrl = 'https://dealexpertsrealestate.bitrix24.com/rest/76/wbocw4wnp63fyits/crm.item.list';

                                                const data = {
                                                    "entityTypeId": <?= LOCATIONS_ENTITY_TYPE_ID ?>,
                                                    "select": ["id", "ufCrm48Location"],
                                                    "filter": {
                                                        "%ufCrm48Location": selectedSubCommunity
                                                    }

                                                };
                                                fetch(webhookUrl, {
                                                        method: 'POST',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'Accept': 'application/json'
                                                        },
                                                        body: JSON.stringify(data)
                                                    })
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        console.log('Community Details:', data);
                                                        const buildings = data.result.items.map(item => {
                                                            const location = item.ufCrm48Location.split(' - ');
                                                            return {
                                                                id: item.id,
                                                                building: location[3] ?? null
                                                            }
                                                        });
                                                        const buildings_set = new Set(buildings.map(building_data => building_data.building));
                                                        BuildingContainer.innerHTML = '';
                                                        BuildingContainer.appendChild(new Option('Select Building', ''));
                                                        Array.from(buildings_set).forEach(building => {
                                                            if (building != null) {
                                                                const option = document.createElement('option');
                                                                option.value = building;
                                                                option.text = building;
                                                                BuildingContainer.appendChild(option);
                                                            }

                                                        });
                                                    })
                                                    .catch(error => {
                                                        console.error('Error fetching Subcommunity details:', error);
                                                    });
                                            }
                                        }
                                    });
                                </script>
                                <div class="col-md-3">
                                    <label for="building" class="form-label">Building</label>
                                    <!-- <input type="text" id="building" name="building" class="form-control" value=""> -->
                                    <select id="building" name="building" class="form-select">
                                        <option value="">Select Building</option>
                                        <!-- <?php
                                                foreach ($buildings as $building) {
                                                    echo '<option value="' . $building['ufCrm62Building'] . '">' . $building['ufCrm62Building'] . '</option>';
                                                }
                                                ?> -->
                                    </select>
                                </div>
                            </div>
                            <div class="row g-3 mt-3">
                                <div class="col-md-3">
                                    <label for="unitNo" class="form-label">Unit No.</label>
                                    <input type="text" id="unitNo" name="unitNo" class="form-control" value="">
                                </div>
                                <div class="col-md-3">
                                    <label for="permit" class="form-label">Permit # or DMTC #</label>
                                    <input type="text" id="permit" name="permit" class="form-control" value="">
                                </div>
                                <div class="col-md-3">
                                    <label for="listingOwner" class="form-label">Listing Owner</label>
                                    <input type="text" id="listingOwner" name="listingOwner" class="form-control" value="">
                                </div>
                                <div class="col-md-3">
                                    <label for="listingTitle" class="form-label">Listing Title</label>
                                    <input type="text" id="listingTitle" name="listingTitle" class="form-control" value="">
                                </div>
                            </div>
                            <div class="row g-3 mt-3">
                                <div class="col-md-3">
                                    <label for="category" class="form-label">Category</label>
                                    <input type="text" id="category" name="category" class="form-control" value="">
                                </div>
                                <div class="col-md-3">
                                    <label for="propertyType" class="form-label">Property Type</label>
                                    <select id="propertyType" name="propertyType" class="form-select">
                                        <option value="">Select Property Type</option> <!-- Placeholder -->
                                        <?php foreach ($property_types as $code => $name): ?>
                                            <option value="<?= $code ?>"><?= $name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="saleRent" class="form-label">Sale/ Rent</label>
                                    <input type="text" id="saleRent" name="saleRent" class="form-control" value="">
                                </div>
                                <div class="col-md-3">
                                    <label for="listingAgents" class="form-label">Property Listing</label>
                                    <select id="listingAgents" name="listingAgents" class="form-select">
                                        <option value="">Select Agent</option> <!-- Placeholder -->
                                        <?php
                                        foreach ($agents as $agent) {
                                            echo '<option value="' . $agent['ufCrm46AgentName'] . '">' . $agent['ufCrm46AgentName'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row g-3 mt-3">
                                <div class="col-md-3">
                                    <label for="landlord" class="form-label">Landlord</label>
                                    <select id="landlord" name="landlord" class="form-select">
                                        <option value="">Select Landlord</option> <!-- Placeholder -->
                                        <?php
                                        foreach ($landlords as $landlord) {
                                            echo '<option value="' . $landlord['ufCrm50LandlordName'] . '">' . $landlord['ufCrm50LandlordName'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="landlordEmail" class="form-label">Landlord Email</label>
                                    <input type="email" id="landlordEmail" name="landlordEmail" class="form-control" value="">
                                </div>
                                <div class="col-md-3">
                                    <label for="landlordPhone" class="form-label">Landlord Phone</label>
                                    <input type="text" id="landlordPhone" name="landlordPhone" class="form-control" value="">
                                </div>
                                <div class="col-md-3">
                                    <label for="bedrooms" class="form-label">Bedrooms <span id="selectedBedrooms"></span></label>
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">1</span>
                                        <input type="range" id="bedrooms" name="bedrooms" class="form-range flex-grow-1" min="1" max="7" value="1">
                                        <span class="ms-2">7</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3 mt-3">
                                <div class="col-md-3">
                                    <label for="developers" class="form-label">Developers</label>
                                    <select id="developers" name="developers" class="form-select">
                                        <option value="">Select Developer</option> <!-- Placeholder -->
                                        <?php
                                        foreach ($developers as $developer) {
                                            echo '<option value="' . $developer['ufCrm44DeveloperName'] . '">' . $developer['ufCrm44DeveloperName'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="price" class="form-label">Price <span id="selectedPrice"></span></label>
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">0</span>
                                        <input type="range" id="price" name="price" class="form-range flex-grow-1" min="0" max="479999000" value="0">
                                        <span class="ms-2">479999000</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="portals" class="form-label">Portals</label>
                                    <input type="text" id="portals" name="portals" class="form-control" value="">
                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="modal-footer d-flex gap-2">
                        <button type="reset" form="filterForm" class="btn btn-outline-secondary">Reset</button>
                        <button type="submit" form="filterForm" class="btn btn-primary">Apply</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function prepareFilters() {
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const params = new URLSearchParams();

        const entriesArray = Array.from(formData.entries());
        console.log('Form Data Entries:', entriesArray);

        for (const [key, value] of entriesArray) {
            if (value != null && value != "" && value != "0" && value != "1") {
                params.append(key, value);
            }
        }

        console.log('Query Parameters:', params.toString());

        window.location.href = form.action + '?' + params.toString();

        return false;
    }
</script>