<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $hidden = [
        'updated_at',
    ];

    function from()
    {
        return $this->belongsTo(Wallet::class, 'from_id');
    }

    function to()
    {
        return $this->belongsTo(Wallet::class, 'to_id');
    }
}
