@extends('layouts.app')

@section('content')
    <div class="row align-items-center mb-4">
        <div class="col-sm-6">
            <h1 class="h3 mb-1">Device Types</h1>
            <p class="text-muted mb-0">Manage device type data</p>
        </div>
        <div class="col-sm-6">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary" id="addDeviceTypeBtn">
                    Add Device Type
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped text-nowrap w-100" id="dataDeviceTypes">
                            <thead>
                                <tr>
                                    <th style="width: 30px;">No</th>
                                    <th>Device Type Name</th>
                                    <th style="width: 30px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('master-data.device-types.partials.form-modal')
    @include('master-data.device-types.partials.script')
@endsection
