<?php

namespace App\Models\Concerns;

use App\Models\ProductImage;
use Illuminate\Support\Collection;

trait HasProductImage
{
    public function getImageUrlAttribute(): string
    {
        $image = $this->relationLoaded('images')
            ? $this->images->sortByDesc('is_main')->first()
            : $this->images()->orderByDesc('is_main')->oldest('id')->first();

        if ($image) {
            return $image->image_url;
        }

        if ($this->main_image) {
            return asset('storage/'.ltrim($this->main_image, '/'));
        }

        return asset('images/product-placeholder.svg');
    }

    public function getGalleryImagesAttribute(): Collection
    {
        $images = $this->relationLoaded('images')
            ? $this->images->sortByDesc('is_main')->values()
            : $this->images()->orderByDesc('is_main')->oldest('id')->get();

        if ($images->isNotEmpty()) {
            return $images;
        }

        if ($this->main_image) {
            return collect([
                new ProductImage([
                    'image_path' => $this->main_image,
                    'is_main' => true,
                ]),
            ]);
        }

        return collect();
    }
}
