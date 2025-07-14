<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = ['user_id', 'total_price', 'status', 'address', 'payment_method'];

    // Relationship to the user who placed the order
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to the order items
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function isCancellable()
    {
        return $this->cancellable && $this->status === 'pending';
    }

}
