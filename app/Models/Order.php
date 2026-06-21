<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const PAYMENT_METHOD_CASH_ON_DELIVERY = 'cash_on_delivery';

    public const PAYMENT_METHOD_BANK_TRANSFER = 'bank_transfer';

    public const PAYMENT_METHOD_PAYPAL = 'paypal';

    public const PAYMENT_METHOD_CARD = 'card';

    public const PAYMENT_STATUS_PENDING = 'pending';

    public const PAYMENT_STATUS_AWAITING_TRANSFER = 'awaiting_transfer';

    public const PAYMENT_STATUS_PAID = 'paid';

    public const PAYMENT_STATUS_FAILED = 'failed';

    public const PAYMENT_STATUS_REFUNDED = 'refunded';

    public const PAYMENT_STATUS_CASH_ON_DELIVERY = 'cash_on_delivery';

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
        'payment_reference',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function paymentMethods(): array
    {
        return [
            self::PAYMENT_METHOD_CASH_ON_DELIVERY,
            self::PAYMENT_METHOD_BANK_TRANSFER,
            self::PAYMENT_METHOD_PAYPAL,
            self::PAYMENT_METHOD_CARD,
        ];
    }

    public static function paymentStatuses(): array
    {
        return [
            self::PAYMENT_STATUS_PENDING,
            self::PAYMENT_STATUS_AWAITING_TRANSFER,
            self::PAYMENT_STATUS_PAID,
            self::PAYMENT_STATUS_FAILED,
            self::PAYMENT_STATUS_REFUNDED,
            self::PAYMENT_STATUS_CASH_ON_DELIVERY,
        ];
    }

    public static function paymentStatusForMethod(string $paymentMethod): string
    {
        return match ($paymentMethod) {
            self::PAYMENT_METHOD_BANK_TRANSFER => self::PAYMENT_STATUS_AWAITING_TRANSFER,
            self::PAYMENT_METHOD_PAYPAL,
            self::PAYMENT_METHOD_CARD => self::PAYMENT_STATUS_PENDING,
            default => self::PAYMENT_STATUS_CASH_ON_DELIVERY,
        };
    }
}
