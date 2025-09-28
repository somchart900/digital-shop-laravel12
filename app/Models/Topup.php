<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topup extends Model
{
    protected $table = 'topups';
    protected $fillable = [
        'user_id',
        'channel',
        'status',
        'amount',
        'link',
        'remark',
        'created_at'
    ];
   public  $timestamps = true;
   public  const UPDATED_AT = null;
  
    public  function user()
    {
        return $this->belongsTo(User::class);
    }
}
