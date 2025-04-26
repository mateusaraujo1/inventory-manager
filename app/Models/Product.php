<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'value',
        'quantity',
        'status',
        'condition',
        'buy_date',
        'description'
    ];

    public function sales()
    {
        return $this->hasMany('App\Models\Sale');
    }
}
