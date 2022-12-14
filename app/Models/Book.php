<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
        'slug',
        'default_image',
        'quantity',
        'sold',
        'category_id',
        'sub_category_id',
        'formality_id',
        'supplier_id',
        'status',
        'created_at',
        'updated_at',

    ];

    // time


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function formality()
    {
        return $this->belongsTo(Formality::class, 'formality_id');
    }
    public function rating()
    {
        return $this->hasMany(Rating::class, 'book_id');
    }
    public function book_detail()
    {
        return $this->hasOne(BookDetail::class, 'book_id');
    }
    public function images()
    {
        return $this->hasMany(Image::class, 'book_id');
    }
    public function order_detail()
    {
        return $this->hasMany(OrderDetail::class, 'book_id');
    }
}
