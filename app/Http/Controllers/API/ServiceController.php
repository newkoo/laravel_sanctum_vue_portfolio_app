<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function get_all_services()
    {
        $services = Service::orderBy('id', 'DESC')->get();

        $response = [
            'services' => $services
        ];
        return response()->json($response, 200);
    }

    public function create_service(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $service = new Service();
        $service->name = $request->name;
        $service->icon = $request->icon;
        $service->description = $request->description;
        $service->save();
    }

    public function update_service(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $service = Service::find($id);
        $service->name = $request->name;
        $service->icon = $request->icon;
        $service->description = $request->description;
        $service->save();
    }

    public function delete_service($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
    }
}
