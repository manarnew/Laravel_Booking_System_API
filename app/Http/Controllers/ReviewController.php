<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::paginate(10);
        return response()->json($reviews);
    }

    public function business_review($id)
    {
        $reviews = Review::where('business_id', $id)->paginate(10);
        return response()->json($reviews);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'review' => 'required|string',
            'business_id' => 'required|exists:businesses,id',
            'stars' => 'required|integer|between:1,5',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 400);
        }

        $review = Review::create([
            'review' => $request->review,
            'stars' => $request->stars,
            'business_id' => $request->business_id,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Review created successfully', 'data' => $review], 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'review' => 'required|string',
            'business_id' => 'required|exists:businesses,id',
            'stars' => 'required|integer|between:1,5',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 400);
        }

        $review = Review::find($id);

        if (!$review || $review->user_id !== Auth::id()) {
            return response()->json(['status' => 'error', 'message' => 'Review not found or unauthorized'], 404);
        }

        $review->update([
            'review' => $request->review,
            'stars' => $request->stars,
            'business_id' => $request->business_id,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Review updated successfully', 'data' => $review], 200);
    }

    public function destroy($id)
    {
        $review = Review::find($id);

        if (!$review || $review->user_id !== Auth::id()) {
            return response()->json(['status' => 'error', 'message' => 'Review not found or unauthorized'], 404);
        }

        $review->delete();
        return response()->json(['status' => 'success', 'message' => 'Review deleted successfully'], 200);
    }
}