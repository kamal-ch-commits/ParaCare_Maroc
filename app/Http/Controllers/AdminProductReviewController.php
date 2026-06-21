<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminProductReviewController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();
        $rating = $request->integer('rating');
        $search = $request->string('search')->toString();

        $reviews = ProductReview::with(['product.category', 'user'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($rating, fn ($query) => $query->where('rating', $rating))
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('comment', 'like', "%{$search}%")
                        ->orWhere('guest_name', 'like', "%{$search}%")
                        ->orWhereHas('product', fn ($productQuery) => $productQuery->searchLocalized($search, ['name']));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $statusBreakdown = ProductReview::query()
            ->select('status')
            ->selectRaw('COUNT(*) as reviews_count')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        return view('reviews.index', compact('reviews', 'status', 'rating', 'search'))
            ->with([
                'summary' => [
                    'total' => ProductReview::count(),
                    'pending' => ProductReview::where('status', ProductReview::STATUS_PENDING)->count(),
                    'approved' => ProductReview::where('status', ProductReview::STATUS_APPROVED)->count(),
                    'rejected' => ProductReview::where('status', ProductReview::STATUS_REJECTED)->count(),
                    'average_rating' => (float) ProductReview::avg('rating'),
                ],
                'statusBreakdown' => $statusBreakdown,
            ]);
    }

    public function approve(ProductReview $review): RedirectResponse
    {
        $review->update(['status' => ProductReview::STATUS_APPROVED]);

        return back()->with('success', __('messages.review_approved'));
    }

    public function reject(ProductReview $review): RedirectResponse
    {
        $review->update(['status' => ProductReview::STATUS_REJECTED]);

        return back()->with('success', __('messages.review_rejected'));
    }

    public function destroy(ProductReview $review): RedirectResponse
    {
        $review->delete();

        return back()->with('success', __('messages.review_deleted'));
    }
}
