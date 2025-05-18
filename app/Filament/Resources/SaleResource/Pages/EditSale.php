<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Form;
use Filament\Forms;

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
            $product->updateStatus();
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
                $product->updateStatus();
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function form(Form $form): Form
    {
        return $form
    ->schema([
        Forms\Components\TextInput::make('sale_value')
            ->required()
            ->numeric(),

        Forms\Components\TextInput::make('pending')
            ->required()
            ->numeric(),

        Select::make('products')
            ->multiple()
            ->relationship('products', 'name', modifyQueryUsing: function ($query) {
                if ($this->record) {
                    $query->where(function ($q) {
                        // Produtos com quantidade > 0
                        $q->where('products.quantity', '>', 0)
                        // OU já associados à venda atual
                        ->orWhereHas('sales', function ($sq) {
                            $sq->where('sales.id', $this->record->id);
                        });
                    });
                } else {
                    // Criação: mostra apenas com quantidade > 0
                    $query->where('products.quantity', '>', 0);
                }
            })
            ->preload()
            ->label('Produtos'),


        Forms\Components\DatePicker::make('sale_date')
            ->required()
            ->default(now()),
    ]);

    }
}
