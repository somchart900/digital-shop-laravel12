<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    public $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'img_link',
        'is_featured'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function item()
    {
        return $this->hasMany(Item::class);
    }
    public  function order()
    {
        return $this->hasMany(Order::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
