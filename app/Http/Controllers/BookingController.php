<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())->with('service')->paginate(10);
        return response()->json($bookings);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'time' => 'required|date',
            'service_id' => 'required|exists:services,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 400);
        }

        $booking = Booking::create([
            'time' => $request->time,
            'service_id' => $request->service_id,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Booking created successfully', 'data' => $booking], 201);
    }

    public function destroy($id)
    {
        $booking = Booking::find($id);

        if (!$booking || $booking->user_id !== Auth::id()) {
            return response()->json(['status' => 'error', 'message' => 'Booking not found or unauthorized'], 404);
        }

        $booking->delete();
        return response()->json(['status' => 'success', 'message' => 'Booking deleted successfully'], 200);
    }
}