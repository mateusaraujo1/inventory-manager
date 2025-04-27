<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    protected function afterCreate(): void
    {
        $sale = $this->record; // Pega a venda recém-criada

        $sale->load('products');

        foreach ($sale->products as $product) {
            $quantitySold = $product->pivot->quantity;

            if ($product->quantity >= $quantitySold) {
                $product->decrement('quantity', $quantitySold);
                $product->updateStatus();
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
