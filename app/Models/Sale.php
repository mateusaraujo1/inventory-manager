<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'sale_value',
        'sale_date'
    ];

    public function products() {
        return $this->belongsToMany(Product::class)->withPivot('quantity')->withTimestamps();
    }

}
