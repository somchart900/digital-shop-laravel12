<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $table = 'otps';
    protected $fillable = [
        'user_id',
        'otp',
        'otp_expired',
    ];
public $timestamps = false; 
  
}
