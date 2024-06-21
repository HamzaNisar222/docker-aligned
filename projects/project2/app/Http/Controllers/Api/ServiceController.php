<?php
namespace App\Http\Controllers\api;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\ServiceCollection;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::all();
        return new ServiceCollection($services);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $service = Service::create($request->all());
        return new ServiceResource($service);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }
        return new ServiceResource($service);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        $service->update($request->all());
        return new ServiceResource($service);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }
        $service->delete();
        return response()->json(['message' => 'Service deleted successfully'], 200);
    }
}
