<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockEntry extends Model
{
    protected $fillable = [
        'product_id',
        'supplier_id',
        'quantity',
        'purchase_price',
        'entry_date',
    ];

    protected function casts(): array
    {
        return [
            'purchase_price' => 'decimal:2',
            'entry_date' => 'date',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
