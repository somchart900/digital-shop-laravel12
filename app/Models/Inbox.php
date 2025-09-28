<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inbox extends Model
{
    protected $table = 'inboxs';

    protected $fillable = [
        'user_id',
        'sender',
        'message',
        'is_read',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
    public $timestamps = true;
    public const UPDATED_AT = null; // ปิด updated_at

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
