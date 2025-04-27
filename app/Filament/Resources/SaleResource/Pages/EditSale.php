<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSale extends EditRecord
{
    protected static string $resource = SaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        $sale = $this->record; // A venda atual

        $sale->load('products'); // Carrega os produtos

        foreach ($sale->products as $product) {
            $quantitySold = $product->pivot->quantity;

            $product->increment('quantity', $quantitySold); // Devolve para o estoque
        }
    }

    protected function afterSave(): void
    {
        $sale = $this->record; // A venda atualizada

        $sale->load('products'); // Carrega os produtos atualizados

        foreach ($sale->products as $product) {
            $quantitySold = $product->pivot->quantity;

            if ($product->quantity >= $quantitySold) {
                $product->decrement('quantity', $quantitySold); // Dá baixa no novo estoque
            }
        }
    }
}
