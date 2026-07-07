<?php

namespace App\Http\Controllers;

use App\Models\DeviceType;
use App\Models\Service;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class ServiceController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $services = Service::query()
                ->join('device_types', 'services.device_type_id', '=', 'device_types.id')
                ->orderBy('device_types.name')
                ->orderBy('services.name')
                ->select('services.*', 'device_types.name as device_type_name');

            return DataTables::of($services)
                ->addIndexColumn()
                ->addColumn('device_type', function ($row) {
                    return $row->deviceType->name ?? '-';
                })
                ->addColumn('price', function ($row) {
                    return 'Rp ' . number_format($row->price, 0, ',', '.');
                })
                ->addColumn('action', function ($row) {
                    return view('master-data.services.partials.action-button', ['id' => $row->id])->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('master-data.services.index', [
            'title' => 'Services',
            'deviceTypes' => DeviceType::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'device_type_id' => 'required|exists:device_types,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('services')->where(function ($query) use ($request) {
                    return $query->where('device_type_id', $request->device_type_id)
                        ->whereNull('deleted_at');
                }),
            ],
            'price' => 'required|numeric|min:0',
        ], [
            'device_type_id.required' => 'Device type is required.',
            'device_type_id.exists' => 'Selected device type is invalid.',
            'name.required' => 'Service name is required.',
            'name.unique' => 'Service name already exists for this device type.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a number.',
            'price.min' => 'Price cannot be negative.',
        ]);

        try {
            Service::create($request->all());

            return response()->json([
                'message' => 'Service successfully added.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Service failed to be saved.'
            ], 500);
        }
    }

    public function edit(Service $service)
    {
        return response()->json([
            'data' => $service,
        ]);
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'device_type_id' => 'required|exists:device_types,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('services')
                    ->where(function ($query) use ($request) {
                        return $query->where('device_type_id', $request->device_type_id)
                            ->whereNull('deleted_at');
                    })
                    ->ignore($service->id),
            ],
            'price' => 'required|numeric|min:0',
        ], [
            'device_type_id.required' => 'Device type is required.',
            'device_type_id.exists' => 'Selected device type is invalid.',
            'name.required' => 'Service name is required.',
            'name.unique' => 'Service name already exists for this device type.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a number.',
            'price.min' => 'Price cannot be negative.',
        ]);

        try {
            $service->update($request->all());

            return response()->json([
                'message' => 'Service successfully updated.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Service failed to be updated.'
            ], 500);
        }
    }

    public function destroy(Service $service)
    {
        try {
            $service->delete();

            return response()->json([
                'message' => 'Service successfully deleted.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Service failed to be deleted.'
            ], 500);
        }
    }

    public function byDeviceType(DeviceType $deviceType)
    {
        $services = $deviceType->services()
            ->orderBy('name')
            ->get(['id', 'name', 'price']);

        return response()->json($services);
    }
}
