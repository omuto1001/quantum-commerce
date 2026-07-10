<?php
// app/Models/PlatformEarning.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformEarning extends Model
{
    protected $fillable = ['order_item_id', 'vendor_id', 'commission_amount'];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}