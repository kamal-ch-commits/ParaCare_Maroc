<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'role',
        'preferred_language',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function productReviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function redirectRouteName(): string
    {
        return $this->isAdmin() ? 'dashboard' : 'storefront.home';
    }

    public static function generateUsernameFromEmail(string $email): string
    {
        $base = Str::limit(Str::slug(Str::before($email, '@'), separator: '_'), 40, '');

        if ($base === '') {
            $base = 'customer';
        }

        $username = $base;
        $suffix = 1;

        while (static::where('username', $username)->exists()) {
            $username = "{$base}_{$suffix}";
            $suffix++;
        }

        return $username;
    }
}
