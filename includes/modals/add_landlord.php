<!-- Modal for Adding Landlord -->
<div class="modal fade" id="addLandlordModal" tabindex="-1" aria-labelledby="addLandlordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLandlordModalLabel">Add Landlord</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addLandlordForm" method="post" action="./landlords.php">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="mobile" class="form-label">Mobile</label>
                        <input type="text" class="form-control" id="mobile" name="mobile" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="addLandlordForm">Add Landlord</button>
            </div>
        </div>
    </div>
</div>
<script>
    const mobileInput = document.querySelector('#addLandlordForm #mobile');
    const webhookUrl = "https://dealexpertsrealestate.bitrix24.com/rest/76/wbocw4wnp63fyits/";

    function debounce(func, delay) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    function checkDuplicateMobile() {
        const mobileValue = mobileInput.value.replace(/[^0-9]/g, '');

        if (mobileValue) {
            const url = webhookUrl + "crm.item.list?entityTypeId=1116&filter[ufCrm50LandlordMobile]=" + mobileValue;
            console.log('URL:', url);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    console.log('Response:', data.result.items);
                    if (data.result.items.length > 0) {
                        alert('Owner with the same mobile number already exists. You cannot add more than one property under the same owner.');
                        mobileInput.value = '';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    }

    mobileInput.addEventListener('input', debounce(checkDuplicateMobile, 500));
</script>