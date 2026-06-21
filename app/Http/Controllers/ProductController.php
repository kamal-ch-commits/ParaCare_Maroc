<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $products = Product::with(['category', 'supplier', 'images'])
            ->when($search, function ($query) use ($search) {
                $query->searchLocalized($search, ['name']);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('products.index', compact('products', 'search'))
            ->with([
                'summary' => [
                    'total' => Product::count(),
                    'low_stock' => Product::lowStock()->count(),
                    'out_of_stock' => Product::outOfStock()->count(),
                    'expiring_soon' => Product::closeToExpiration()->count(),
                    'stock_value' => (float) Product::query()->selectRaw('SUM(stock_quantity * sale_price) as value')->value('value'),
                ],
            ]);
    }

    public function create(): View
    {
        return view('products.create', $this->formData(new Product()));
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $product = Product::create($this->validatedProductData($request));
            $this->syncImages($product, $request);
        });

        return redirect()->route('products.index')->with('success', __('messages.product_created'));
    }

    public function edit(Product $product): View
    {
        return view('products.edit', $this->formData($product->load('images')));
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        DB::transaction(function () use ($request, $product): void {
            $product->update($this->validatedProductData($request));
            $this->syncImages($product, $request);
        });

        return redirect()->route('products.index')->with('success', __('messages.product_updated'));
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->saleDetails()->exists() || $product->orderItems()->exists()) {
            return back()->with('error', __('messages.product_delete_blocked'));
        }

        $this->deleteImages($product->load('images')->images);
        $this->deleteLegacyImageIfNeeded($product);

        $product->delete();

        return redirect()->route('products.index')->with('success', __('messages.product_deleted'));
    }

    private function formData(Product $product): array
    {
        return [
            'product' => $product,
            'productImages' => $product->gallery_images,
            'categories' => Category::orderBy('name')->get(),
            'suppliers' => Supplier::orderBy('name')->get(),
        ];
    }

    private function validatedProductData(ProductRequest $request): array
    {
        return $request->safe()->except([
            'images',
            'main_image_id',
            'delete_image_ids',
        ]);
    }

    private function syncImages(Product $product, ProductRequest $request): void
    {
        $deleteIds = collect($request->validated('delete_image_ids', []))->map(fn ($id) => (int) $id);
        $images = $product->images()->get();

        if ($deleteIds->isNotEmpty()) {
            $imagesToDelete = $images->whereIn('id', $deleteIds->all());
            $this->deleteImages($imagesToDelete);
            $images = $product->images()->get();
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $product->images()->create([
                    'image_path' => $file->store('products', 'public'),
                    'is_main' => false,
                ]);
            }

            $images = $product->images()->get();
        }

        $selectedMainId = $request->validated('main_image_id');
        $mainImage = $selectedMainId
            ? $images->firstWhere('id', (int) $selectedMainId)
            : $images->sortByDesc('is_main')->first();

        if (! $mainImage && $images->isNotEmpty()) {
            $mainImage = $images->first();
        }

        $product->images()->update(['is_main' => false]);

        if ($mainImage) {
            $product->images()->whereKey($mainImage->id)->update(['is_main' => true]);
            $product->forceFill(['main_image' => $mainImage->image_path])->saveQuietly();

            return;
        }

        $this->deleteLegacyImageIfNeeded($product);
        $product->forceFill(['main_image' => null])->saveQuietly();
    }

    private function deleteImages(Collection $images): void
    {
        $images->each(function ($image): void {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        });
    }

    private function deleteLegacyImageIfNeeded(Product $product): void
    {
        if (! $product->main_image) {
            return;
        }

        $isReferenced = $product->images()->where('image_path', $product->main_image)->exists();

        if (! $isReferenced) {
            Storage::disk('public')->delete($product->main_image);
        }
    }
}
