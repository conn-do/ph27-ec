<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public function chirps(): HasMany
    {
        return $this->hasMany(Chirp::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // ▼ 追加：ユーザーのレビュー
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // ▼ 追加：特定の商品を購入したことがあるか判定するメソッド
    public function hasPurchased($productId): bool
    {
        return $this->orders()->whereHas('items', function ($query) use ($productId) {
            $query->where('product_id', $productId);
        })->exists();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->email === 'test@example.com';
    }
}