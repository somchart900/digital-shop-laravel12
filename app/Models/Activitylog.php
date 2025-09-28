<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activitylog extends Model
{
    protected $table = 'activitylogs';
    protected $fillable = ['user_id', 'action', 'description', 'created_at'];

    protected $casts = [
        'created_at' => 'datetime'
    ];
    public $timestamps = true;
    public const UPDATED_AT = null;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
