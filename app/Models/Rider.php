<?php
// app/Models/Rider.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rider extends Model
{
    protected $fillable = [
        'user_id', 'vehicle_type', 'license_plate',
        'national_id_number', 'id_document', 'is_available',
    ];

    // Every rider profile belongs to one user account
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // A rider can be assigned to many order items (deliveries) over time
public function orderItems()
{
    return $this->hasMany(OrderItem::class);
}
}