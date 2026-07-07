@extends('layouts.app')

@section('content')
    <div class="row align-items-center mb-4">
        <div class="col-sm-6">
            <h1 class="h3 mb-1">Services</h1>
            <p class="text-muted mb-0">Manage service data</p>
        </div>
        <div class="col-sm-6">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary" id="addServiceBtn">
                    Add Service
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped text-nowrap w-100" id="dataServices">
                            <thead>
                                <tr>
                                    <th style="width: 30px;">No</th>
                                    <th>Device Type</th>
                                    <th>Service Name</th>
                                    <th>Price</th>
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

    @include('master-data.services.partials.form-modal')
    @include('master-data.services.partials.script')
@endsection
