<div class="modal fade" id="serviceModal" tabindex="-1" role="dialog" aria-labelledby="serviceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="serviceModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="serviceForm">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="device_type_id">Device Type</label>
                        <select class="form-control form-select" id="device_type_id" name="device_type_id" required>
                            <option value="" disabled selected></option>
                            @foreach ($deviceTypes as $deviceType)
                                <option value="{{ $deviceType->id }}">{{ $deviceType->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="name">Service Name</label>
                        <input type="text" class="form-control form-control-user" id="name" name="name"
                            required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="price">Price</label>
                        <input type="number" step="0.01" min="0" class="form-control form-control-user"
                            id="price" name="price" required>
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
