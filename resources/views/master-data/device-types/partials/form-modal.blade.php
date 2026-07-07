<div class="modal fade" id="deviceTypeModal" tabindex="-1" role="dialog" aria-labelledby="deviceTypeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deviceTypeModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deviceTypeForm">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="name">Device Type Name</label>
                        <input type="text" class="form-control form-control-user" id="name" name="name"
                            required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary" id="submitBtn">Save Change</button>
                </div>
            </form>
        </div>
    </div>
</div>
