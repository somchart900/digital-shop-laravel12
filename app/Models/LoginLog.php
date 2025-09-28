<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    protected $table = 'loginlogs';

    protected $fillable = [
        'user_id',
        'ip',
        'browser',
        'os',
        'created_at'
    ];
       protected $casts = [
        'created_at' => 'datetime',
    ];

    public const UPDATED_AT = null;
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
