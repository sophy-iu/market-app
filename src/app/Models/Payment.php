<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_method',
    ];

    public function purchase()
    {
        return $this->hasOne('App\Models\Purchase');
    }
}
