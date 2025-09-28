<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $table = 'credits';
    protected $fillable = [
        'user_id',
        'amount',
        'created_at',
        'updated_at',
    ];
   
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
