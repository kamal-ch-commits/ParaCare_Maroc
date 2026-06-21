<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasLocalizedAttributes;

    protected $fillable = [
        'name',
        'name_translations',
        'description',
        'description_translations',
    ];

    protected function casts(): array
    {
        return [
            'name_translations' => 'array',
            'description_translations' => 'array',
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
