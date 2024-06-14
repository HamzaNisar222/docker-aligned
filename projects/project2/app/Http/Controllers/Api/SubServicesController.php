<?php

namespace App\Http\Controllers\api;

use App\Models\Service;
use App\Models\Subservice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\SubServiceResource;
use App\Http\Requests\ServiceRequestUpdate;
use App\Http\Resources\SubServiceCollection;

class SubServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($serviceId)
    {
        $service = Service::find($serviceId);
        if(!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }
        $sub_service = $service->subServices;
        return new SubServiceCollection($sub_service);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceRequest $request, $serviceId)
    {
        $service = Service::find($serviceId);
        if(!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }
        $create_sub_service = $service->subServices()->create($request->all());
        return new SubServiceResource($create_sub_service);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ServiceRequestUpdate $request, $id)
    {
        $subService = Subservice::find($id);

        if (!$subService) {
            return response()->json([
                'message' => 'Sub-Service not found'
            ], 404);
        }
        $subService->update($request->all());
        return new SubServiceResource($subService);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subService = Subservice::find($id);
        if(!$subService) {
            return response()->json([
                'message' => 'Sub-Service not found'
            ], 404);
        }
        $subService->delete($id);
        return response()->json([
            'message' => 'Sub-Service Delete Successfully'
        ], 201);
    }
}
