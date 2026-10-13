<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = ['invoice_number', 'cashier_id', 'transaction_at', 'customer_name', 'total_amount', 'paid_amount', 'change_amount', 'payment_method', 'notes'];

    protected function casts(): array
    {
        return ['transaction_at' => 'datetime', 'total_amount' => 'integer', 'paid_amount' => 'integer', 'change_amount' => 'integer'];
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}
