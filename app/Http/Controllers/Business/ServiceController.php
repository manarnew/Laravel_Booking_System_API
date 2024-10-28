<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function index()
    {
        $business =  Business::select('id')->where('user_id',Auth::id())->first();
        $services = Service::where('business_id',$business->id)->paginate(10);
        return response()->json(Auth::user()->role);
    }

    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'price' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->error()->toJson());
        }
        $business =  Business::select('id')->where('user_id',Auth::id())->first();
        $service = new Service();
        $service->name = $request->name;
        $service->description = $request->description;
        $service->business_id = $business->id;
        $service->price = $request->price;
        $service->save();
        return response()->json(['status'=>'success','message'=>'Service is created successfully'],200);
    }
    public function update(Request $request,$id)
    {
        $service = Service::findOrfail($id);
        $service->name = $request->name;
        $service->description = $request->description;
        $service->price = $request->price;
        $service->save();
        return response()->json(['status'=>'success','message'=>'Service is updated successfully'],200);
    }
    public function destroy($id)
    {
        $service = Service::findOrfail($id);
        $service->delete();
        return response()->json(['status'=>'success','message'=>'Service is deleted successfully'],200);
    }
}
