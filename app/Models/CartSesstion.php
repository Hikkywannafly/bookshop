<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartSesstion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'error',
        'created_at',
        'updated_at',
    ];

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'user_id');
    // }
    // cart sesstion with user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function cart_items()
    {
        return $this->hasMany(CartItem::class, 'cart_sesstion_id');
    }
}
