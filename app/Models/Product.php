<?php

namespace App\Models;

use App\Models\Concerns\HasProductImage;
use App\Models\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

class Product extends Model
{
    use HasLocalizedAttributes;
    use HasProductImage;

    protected $fillable = [
        'code',
        'name',
        'name_translations',
        'category_id',
        'supplier_id',
        'purchase_price',
        'sale_price',
        'stock_quantity',
        'minimum_threshold',
        'expiration_date',
        'description',
        'description_translations',
        'main_image',
    ];

    protected function casts(): array
    {
        return [
            'name_translations' => 'array',
            'purchase_price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'expiration_date' => 'date',
            'description_translations' => 'array',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stockEntries(): HasMany
    {
        return $this->hasMany(StockEntry::class);
    }

    public function saleDetails(): HasMany
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderByDesc('is_main')->oldest('id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class)->latest();
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(ProductReview::class)
            ->where('status', ProductReview::STATUS_APPROVED)
            ->latest();
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_main', true)->oldest('id');
    }

    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereColumn('stock_quantity', '<=', 'minimum_threshold');
    }

    public function scopeOutOfStock(Builder $query): Builder
    {
        return $query->where('stock_quantity', 0);
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->whereNotNull('expiration_date')
            ->whereDate('expiration_date', '<', now()->toDateString());
    }

    public function scopeCloseToExpiration(Builder $query, int $days = 30): Builder
    {
        return $query->whereNotNull('expiration_date')
            ->whereBetween('expiration_date', [
                now()->toDateString(),
                now()->addDays($days)->toDateString(),
            ]);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeSearchLocalized(Builder $query, string $term, array $columns = ['name', 'description'], bool $includeCode = true): Builder
    {
        $locale = app()->getLocale();
        $fallbackLocale = config('app.fallback_locale', 'en');
        $searchTerm = "%{$term}%";

        return $query->where(function (Builder $subQuery) use ($columns, $includeCode, $locale, $fallbackLocale, $searchTerm): void {
            $isFirstCondition = true;

            foreach ($columns as $column) {
                $method = $isFirstCondition ? 'where' : 'orWhere';
                $subQuery->{$method}($column, 'like', $searchTerm);
                $subQuery->orWhereRaw(
                    "JSON_UNQUOTE(JSON_EXTRACT({$column}_translations, '$.\"{$locale}\"')) LIKE ?",
                    [$searchTerm]
                );
                $isFirstCondition = false;

                if ($fallbackLocale !== $locale) {
                    $subQuery->orWhereRaw(
                        "JSON_UNQUOTE(JSON_EXTRACT({$column}_translations, '$.\"{$fallbackLocale}\"')) LIKE ?",
                        [$searchTerm]
                    );
                }
            }

            if ($includeCode) {
                $method = $isFirstCondition ? 'where' : 'orWhere';
                $subQuery->{$method}('code', 'like', $searchTerm);
            }
        });
    }

    public function scopeWithRatingSummary(Builder $query): Builder
    {
        return $query
            ->withCount([
                'approvedReviews as approved_reviews_count',
            ])
            ->withAvg([
                'approvedReviews as approved_reviews_avg_rating' => fn (Builder $reviewQuery) => $reviewQuery,
            ], 'rating');
    }

    public function getAverageRatingAttribute(): float
    {
        $average = $this->approved_reviews_avg_rating
            ?? $this->reviews_average_rating
            ?? $this->approvedReviews()->avg('rating');

        return round((float) ($average ?? 0), 1);
    }

    public function getReviewCountAttribute(): int
    {
        return (int) ($this->approved_reviews_count
            ?? $this->reviews_count
            ?? $this->approvedReviews()->count());
    }

    public function getRoundedAverageRatingAttribute(): float
    {
        return round($this->average_rating * 2) / 2;
    }

    public function getRatingStarsAttribute(): Collection
    {
        $rounded = $this->rounded_average_rating;

        return collect(range(1, 5))->map(function (int $position) use ($rounded): string {
            if ($rounded >= $position) {
                return 'full';
            }

            if ($rounded === $position - 0.5) {
                return 'half';
            }

            return 'empty';
        });
    }
}
