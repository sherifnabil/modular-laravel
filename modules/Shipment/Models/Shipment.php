<?php

namespace Modules\Shipment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Shipment\Database\Factories\ShipmentFactory;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'provider',
        'provider_shipment_id',
    ];

    protected static function newFactory() : ShipmentFactory
    {
        return ShipmentFactory::new();
    }
}
