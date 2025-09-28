<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';

    public $fillable = [
        'name',
        'category_id',
        'product_id',
        'description',
        'price',
        'code',        
        'img_link',
        'youtube',
        'article', 
        'external_link',      
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
   
}
