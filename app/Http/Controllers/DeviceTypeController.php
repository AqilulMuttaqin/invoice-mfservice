<?php

namespace App\Http\Controllers;

use App\Models\DeviceType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class DeviceTypeController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $deviceTypes = DeviceType::orderBy('name');

            return DataTables::of($deviceTypes)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('master-data.device-types.partials.action-button', ['id' => $row->id])->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('master-data.device-types.index', [
            'title' => 'Device Types',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('device_types')->whereNull('deleted_at'),
            ],
        ], [
            'name.required' => 'Device type name is required.',
            'name.unique' => 'Device type name already exists.',
        ]);

        try {
            DeviceType::create($request->all());

            return response()->json([
                'message' => 'Device type successfully added.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Device type failed to be saved.'
            ], 500);
        }
    }

    public function edit(DeviceType $deviceType)
    {
        return response()->json([
            'data' => $deviceType,
        ]);
    }

    public function update(Request $request, DeviceType $deviceType)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('device_types')
                    ->whereNull('deleted_at')
                    ->ignore($deviceType->id),
            ],
        ], [
            'name.required' => 'Device type name is required.',
            'name.unique' => 'Device type name already exists.',
        ]);

        try {
            $deviceType->update($request->all());

            return response()->json([
                'message' => 'Device type successfully updated.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Device type failed to be updated.'
            ], 500);
        }
    }

    public function destroy(DeviceType $deviceType)
    {
        if ($deviceType->services()->exists()) {
            return response()->json([
                'message' => 'This device type cannot be deleted because it is still used by one or more services.'
            ], 422);
        }

        try {
            $deviceType->delete();

            return response()->json([
                'message' => 'Device type successfully deleted.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Device type failed to be deleted.'
            ], 500);
        }
    }
}
