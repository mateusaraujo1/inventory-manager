<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use App\Models\Product;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalProductValue extends BaseWidget
{
    protected function getStats(): array
    {
        $totalValue = \App\Models\Product::all()->sum(fn ($product) => $product->totalValue());

    return [
        Stat::make('Valor total em estoque', 'R$ ' . number_format($totalValue, 2, ',', '.')),
    ];
    }
}
