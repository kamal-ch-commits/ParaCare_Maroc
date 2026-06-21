@csrf
<div class="row g-4">
    <div class="col-xl-8">
        <div class="form-section-card p-4 mb-4">
            <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                <div>
                    <h2 class="h5 mb-1">{{ __('admin.product_information') }}</h2>
                    <p class="section-subtitle">{{ __('admin.product_information_description') }}</p>
                </div>
                <span class="badge badge-soft-info">{{ __('admin.required_badge') }}</span>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label" for="code">{{ __('admin.product_code') }}</label>
                    <input type="text" id="code" name="code" value="{{ old('code', $product->code) }}" class="form-control" required>
                </div>
                <div class="col-md-8 mb-3">
                    <label class="form-label" for="name">{{ __('admin.product_name') }}</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="category_id">{{ __('common.category') }}</label>
                    <select id="category_id" name="category_id" class="form-select" required>
                        <option value="">{{ __('admin.select_category') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->translated_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="supplier_id">{{ __('common.supplier') }}</label>
                    <select id="supplier_id" name="supplier_id" class="form-select">
                        <option value="">{{ __('admin.no_supplier') }}</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @selected(old('supplier_id', $product->supplier_id) == $supplier->id)>{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label" for="description">{{ __('common.description') }}</label>
                    <textarea id="description" name="description" class="form-control" rows="4" placeholder="{{ __('common.description') }}">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>
        </div>

        <div class="form-section-card p-4 mb-4">
            <div class="mb-4">
                <h2 class="h5 mb-1">{{ __('admin.pricing_stock') }}</h2>
                <p class="section-subtitle">{{ __('admin.pricing_stock_description') }}</p>
            </div>

            <div class="row">
                <div class="col-md-6 col-lg-3 mb-3">
                    <label class="form-label" for="purchase_price">{{ __('common.purchase_price') }}</label>
                    <input type="number" step="0.01" min="0" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price ?? 0) }}" class="form-control" required>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                    <label class="form-label" for="sale_price">{{ __('common.sale_price') }}</label>
                    <input type="number" step="0.01" min="0" id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price ?? 0) }}" class="form-control" required>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                    <label class="form-label" for="stock_quantity">{{ __('admin.stock_available') }}</label>
                    <input type="number" min="0" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" class="form-control" required>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                    <label class="form-label" for="minimum_threshold">{{ __('admin.minimum_threshold') }}</label>
                    <input type="number" min="0" id="minimum_threshold" name="minimum_threshold" value="{{ old('minimum_threshold', $product->minimum_threshold ?? 0) }}" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="expiration_date">{{ __('admin.expiration_date') }}</label>
                    <input type="date" id="expiration_date" name="expiration_date" value="{{ old('expiration_date', optional($product->expiration_date)->format('Y-m-d')) }}" class="form-control">
                </div>
            </div>
        </div>

        <div class="form-section-card p-4">
            <div class="mb-4">
                <h2 class="h5 mb-1">{{ __('admin.photo_gallery') }}</h2>
                <p class="section-subtitle">{{ __('admin.photo_gallery_description') }}</p>
            </div>

            <div class="mb-4">
                <label class="form-label" for="images">{{ __('admin.new_photos') }}</label>
                <input type="file" id="images" name="images[]" class="form-control" accept="image/*" multiple>
                <div class="form-text">{{ __('admin.images_help') }}</div>
            </div>

            <div id="image-preview-wrapper" class="d-none">
                <label class="form-label">{{ __('admin.new_photos_preview') }}</label>
                <div class="row g-3" id="image-preview-grid"></div>
            </div>

            @if ($productImages->isNotEmpty())
                <div class="mt-4">
                    <label class="form-label d-block">{{ __('admin.saved_photos') }}</label>
                    <div class="row g-3">
                        @foreach ($productImages as $image)
                            <div class="col-md-6 col-xl-4">
                                <div class="image-card h-100">
                                    <img src="{{ $image->image_url }}" alt="{{ __('common.image') }} {{ $loop->iteration }}" class="mb-3">
                                    <div class="form-check mb-2">
                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="main_image_id"
                                            id="main_image_{{ $image->id }}"
                                            value="{{ $image->id }}"
                                            @checked(old('main_image_id', $productImages->firstWhere('is_main', true)?->id) == $image->id)
                                        >
                                        <label class="form-check-label" for="main_image_{{ $image->id }}">
                                            {{ __('admin.use_as_main_photo') }}
                                        </label>
                                    </div>
                                    <div class="form-check text-danger">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="delete_image_ids[]"
                                            id="delete_image_{{ $image->id }}"
                                            value="{{ $image->id }}"
                                            @checked(collect(old('delete_image_ids', []))->contains($image->id))
                                        >
                                        <label class="form-check-label" for="delete_image_{{ $image->id }}">
                                            {{ __('admin.delete_photo') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="helper-note">
                    {{ __('admin.no_additional_photos') }}
                </div>
            @endif
        </div>
    </div>
    <div class="col-xl-4">
        <div class="form-section-card p-4 mb-4">
            <div class="mb-3">
                <h2 class="h5 mb-1">{{ __('admin.primary_photo') }}</h2>
                <p class="section-subtitle">{{ __('admin.primary_photo_description') }}</p>
            </div>
            <img src="{{ $product->image_url }}" alt="{{ $product->translated_name ?: __('common.product') }}" class="img-fluid rounded-4 border">
        </div>

        <div class="helper-note mb-4">
            {{ __('admin.photo_tip') }}
        </div>

        <div class="form-section-card p-4">
            <h2 class="h5 mb-2">{{ __('admin.quick_checklist') }}</h2>
            <ul class="mb-0 ps-3 text-secondary">
                <li>{{ __('admin.product_checklist_name') }}</li>
                <li>{{ __('admin.product_checklist_price') }}</li>
                <li>{{ __('admin.product_checklist_stock') }}</li>
                <li>{{ __('admin.product_checklist_photo') }}</li>
            </ul>
        </div>
    </div>
</div>
<div class="d-flex flex-column flex-sm-row gap-2 mt-4">
    <button class="btn btn-primary" type="submit">{{ __('common.save') }}</button>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">{{ __('common.cancel') }}</a>
</div>

@push('scripts')
    <script>
        const input = document.getElementById('images');
        const wrapper = document.getElementById('image-preview-wrapper');
        const grid = document.getElementById('image-preview-grid');

        if (input && wrapper && grid) {
            input.addEventListener('change', () => {
                grid.innerHTML = '';

                if (! input.files.length) {
                    wrapper.classList.add('d-none');
                    return;
                }

                wrapper.classList.remove('d-none');

                Array.from(input.files).forEach((file, index) => {
                    const reader = new FileReader();

                    reader.addEventListener('load', (event) => {
                        const col = document.createElement('div');
                        col.className = 'col-md-6 col-xl-4';
                        col.innerHTML = `
                            <div class="image-card h-100">
                                <img src="${event.target?.result}" alt="${@js(__('admin.new_photos_preview'))} ${index + 1}" class="mb-3">
                                <div class="small text-secondary">
                                    ${index === 0 ? @js(__('admin.first_new_photo_note')) : @js(__('admin.additional_photo_note'))}
                                </div>
                            </div>
                        `;
                        grid.appendChild(col);
                    });

                    reader.readAsDataURL(file);
                });
            });
        }
    </script>
@endpush
