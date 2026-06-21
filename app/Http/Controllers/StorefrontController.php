<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function home(): View
    {
        return view('storefront.home', [
            'featuredProducts' => Product::with(['category', 'images'])
                ->withRatingSummary()
                ->available()
                ->latest()
                ->take(8)
                ->get(),
            'categories' => Category::withCount('products')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $categoryId = $request->integer('category');
        $minPrice = $request->filled('min_price') ? (float) $request->input('min_price') : null;
        $maxPrice = $request->filled('max_price') ? (float) $request->input('max_price') : null;
        $stockStatus = $request->string('stock_status')->toString() ?: 'all';
        $sort = $request->string('sort')->toString() ?: 'newest';

        $products = Product::with(['category', 'images'])
            ->withRatingSummary()
            ->when($search, function ($query) use ($search) {
                $query->searchLocalized($search, ['name', 'description']);
            })
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->when($minPrice !== null, fn ($query) => $query->where('sale_price', '>=', $minPrice))
            ->when($maxPrice !== null, fn ($query) => $query->where('sale_price', '<=', $maxPrice))
            ->when($stockStatus === 'in_stock', fn ($query) => $query->where('stock_quantity', '>', 0))
            ->when($stockStatus === 'out_of_stock', fn ($query) => $query->where('stock_quantity', 0))
            ->when($sort === 'price_asc', fn ($query) => $query->orderBy('sale_price'))
            ->when($sort === 'price_desc', fn ($query) => $query->orderByDesc('sale_price'))
            ->when($sort === 'best_rated', fn ($query) => $query->orderByDesc('approved_reviews_avg_rating')->orderByDesc('approved_reviews_count')->latest())
            ->when($sort === 'most_reviewed', fn ($query) => $query->orderByDesc('approved_reviews_count')->latest())
            ->when($sort === 'name_asc', fn ($query) => $query->orderBy('name'))
            ->when($sort === 'newest', fn ($query) => $query->latest())
            ->paginate(9)
            ->withQueryString();

        return view('storefront.index', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
            'search' => $search,
            'selectedCategory' => $categoryId,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'stockStatus' => $stockStatus,
            'sort' => $sort,
        ]);
    }

    public function show(Product $product): View
    {
        $product->load([
            'category',
            'images',
            'approvedReviews.user',
        ]);

        $userReview = auth()->check() && auth()->user()->isCustomer()
            ? ProductReview::where('product_id', $product->id)->where('user_id', auth()->id())->first()
            : null;

        return view('storefront.show', [
            'product' => $product->loadCount([
                'approvedReviews as approved_reviews_count',
            ])->loadAvg([
                'approvedReviews as approved_reviews_avg_rating' => fn ($query) => $query,
            ], 'rating'),
            'relatedProducts' => Product::with(['category', 'images'])
                ->withRatingSummary()
                ->whereKeyNot($product->id)
                ->where('category_id', $product->category_id)
                ->latest()
                ->take(4)
                ->get(),
            'userReview' => $userReview,
        ]);
    }
}
