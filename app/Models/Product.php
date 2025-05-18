<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'value',
        'quantity',
        'reserved',
        'status',
        'condition',
        'buy_date',
        'description'
    ];

    public function sales()
    {
        return $this->belongsToMany(Sale::class)->withPivot('quantity')->withTimestamps();
    }

    public function totalValue() {
        return $this->value * $this->quantity;
    }

    public function updateStatus()
    {
        $this->status = $this->quantity == 0 ? 'esgotado' : 'em estoque';
        $this->reserved = $this->quantity == 0 ? 0 : $this->reserved;
        $this->save();
    }
}
