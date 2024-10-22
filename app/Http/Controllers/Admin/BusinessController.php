<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusinessController extends Controller
{
    public function index()
    {
        $business =  Business::paginate(10);
        return response()->json($business);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'name' => 'required',
            'opening_hours' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->error()->toJson());
        }
        Business::create(array_merge($validator->validated()));
        return response()->json('Business is created successfully');
    }

    public function update(Request $request,$id)
    {
        $business = Business::findOrfail($id);
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'name' => 'required',
            'opening_hours' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->error()->toJson());
        }
        $business->update(array_merge($validator->validated()));
        return response()->json('Business is updated successfully');
    }
    
    public function destory($id)
    {
        $business = Business::findOrfail($id);
        $business->delete();
        return response()->json('Business is deleted successfully');
    }
}
