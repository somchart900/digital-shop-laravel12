<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        'name',
        'description',
        'is_featured',
        'img_link',
    ];
    public function product()
    {
        return $this->hasMany(Product::class);
    }
}
