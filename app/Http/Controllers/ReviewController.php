<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\ReviewVote;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title'  => 'nullable|string|max:150',
            'body'   => 'nullable|string|max:2000',
        ]);

        $user = auth()->user();

        // Prevent duplicate reviews
        if ($product->reviews()->where('user_id', $user->id)->exists()) {
            return back()
                ->with('review_error', __('front.review_already_submitted'))
                ->withFragment('reviews');
        }

        // Check for a delivered order containing this product (verified purchase)
        $order = Order::where('user_id', $user->id)
            ->where('status', Order::STATUS_DELIVERED)
            ->whereHas('items', fn($q) => $q->where('product_id', $product->id))
            ->first();

        $product->reviews()->create([
            'user_id'     => $user->id,
            'order_id'    => $order?->id,
            'rating'      => $request->rating,
            'title'       => $request->title,
            'body'        => $request->body,
            'is_approved' => false,
        ]);

        return back()
            ->with('review_success', __('front.review_submitted'))
            ->withFragment('reviews');
    }

    public function vote(Request $request, Review $review)
    {
        $userId = auth()->id();

        $existing = ReviewVote::where('review_id', $review->id)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            // Toggle: if already voted helpful, remove the vote
            $existing->delete();
            $review->decrement('helpful_count');
            return response()->json(['helpful_count' => $review->fresh()->helpful_count, 'voted' => false]);
        }

        ReviewVote::create([
            'review_id'  => $review->id,
            'user_id'    => $userId,
            'is_helpful' => true,
        ]);

        $review->increment('helpful_count');

        return response()->json(['helpful_count' => $review->fresh()->helpful_count, 'voted' => true]);
    }
}
