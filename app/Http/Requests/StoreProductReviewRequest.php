<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isCustomer() ?? false;
    }

    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:3', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'rating' => __('common.rating'),
            'comment' => __('common.comment'),
        ];
    }
}
