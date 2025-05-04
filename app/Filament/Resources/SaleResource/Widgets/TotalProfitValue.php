<?php

namespace App\Filament\Resources\SaleResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalProfitValue extends BaseWidget
{
    protected function getStats(): array
    {
        
        $totalProfit = \App\Models\Sale::all()->sum(fn ($sale) => $sale->profit());

        return [
            Stat::make('Lucro total de vendas', 'R$ ' . number_format($totalProfit, 2, ',', '.')),
        ];
    
    }
}