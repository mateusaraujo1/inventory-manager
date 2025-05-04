<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InventoryOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalValue = Product::all()->sum(fn ($product) => $product->totalValue());

        return [
            Stat::make('Valor total em estoque', 'R$ ' . number_format($totalValue, 2, ',', '.')),
        ];
    }

    protected function getMaxWidth(): MaxWidth | string | null
    {
        return MaxWidth::Full; // ou Small, Medium, Large
    }
}
