<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $hidden = [
        'deleted_at',
    ];

    function user()
    {
        return $this->belongsTo(User::class);
    }

}
