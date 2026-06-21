<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $product = $this->route('product');

        return [
            'code' => ['required', 'string', 'max:100', Rule::unique('products', 'code')->ignore($product)],
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'minimum_threshold' => ['required', 'integer', 'min:0'],
            'expiration_date' => ['nullable', 'date'],
            'description' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:3072'],
            'main_image_id' => array_values(array_filter([
                'nullable',
                'integer',
                $product
                    ? Rule::exists('product_images', 'id')->where(fn ($query) => $query->where('product_id', $product->id))
                    : null,
            ])),
            'delete_image_ids' => ['nullable', 'array'],
            'delete_image_ids.*' => array_values(array_filter([
                'integer',
                $product
                    ? Rule::exists('product_images', 'id')->where(fn ($query) => $query->where('product_id', $product->id))
                    : null,
            ])),
        ];
    }
}
