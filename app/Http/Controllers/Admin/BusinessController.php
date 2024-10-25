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
        $businesses = Business::paginate(10);
        return response()->json($businesses);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'name' => 'required|string|max:255',
            'opening_hours' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 400);
        }

        $business = Business::create($validator->validated());
        return response()->json(['status' => 'success', 'message' => 'Business created successfully', 'data' => $business], 201);
    }

    public function update(Request $request, $id)
    {
        $business = Business::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'name' => 'required|string|max:255',
            'opening_hours' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 400);
        }

        $business->update($validator->validated());
        return response()->json(['status' => 'success', 'message' => 'Business updated successfully', 'data' => $business], 200);
    }
    
    public function destroy($id)
    {
        $business = Business::findOrFail($id);
        $business->delete();
        return response()->json(['status' => 'success', 'message' => 'Business deleted successfully'], 200);
    }
}