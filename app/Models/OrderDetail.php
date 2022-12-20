<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'payment_id',
        'total',
        'status',
        'province',
        'ward',
        'district',
        'recipient',
        'note',
        'address',
        'phone',
        'email',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    // payment
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
    // user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
