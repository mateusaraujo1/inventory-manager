<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'sale_value',
        'sale_date'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity')->withTimestamps();
    }

    /**
     * Dá baixa no estoque com base nos produtos da venda.
     */
    public function adjustInventory()
    {
        foreach ($this->products as $product) {
            $quantitySold = $product->pivot->quantity;
            $product->decrement('quantity', $quantitySold);
        }
    }

    /**
     * Restaura o estoque antes de alterar ou excluir uma venda.
     */
    public function restoreInventory()
    {
        // Buscar os produtos originais da venda no banco
        $originalProducts = $this->products()->get();

        foreach ($originalProducts as $product) {
            $quantitySold = $product->pivot->quantity;
            $product->increment('quantity', $quantitySold);
        }
    }

    protected static function booted()
    {
        static::deleting(function (Sale $sale) {
            $sale->restoreInventory();
        });
    }

    
}
