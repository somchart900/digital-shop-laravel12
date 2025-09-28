<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'username',
        'category_id',
        'product_id',
        'name',
        'description', 
        'price',
        'code',
        'youtube',
        'img_link',
        'article',
        'created_at'
    ];

    public const UPDATED_AT = null;
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
