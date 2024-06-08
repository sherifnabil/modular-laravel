<?php

namespace Modules\Order\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Order\Database\Factories\OrderFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total_in_piasters',
        'payment_gateway',
        'payment_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'total_in_piasters' => 'integer',
    ];


    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lines() : HasMany
    {
        return $this->hasMany(OrderLine::class, 'order_id');
    }
}
