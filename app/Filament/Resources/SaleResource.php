<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Filament\Resources\SaleResource\RelationManagers;
use App\Filament\Resources\SaleResource\Widgets\TotalProfitValue;
use App\Models\Sale;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
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
                    ->relationship('products', 'name', function ($query) {
                        $query->where('products.quantity', '>', 0);
                    })
                    ->preload(),
                Forms\Components\DatePicker::make('sale_date')
                    ->required()
                    ->default(now()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('products_list')
                    ->label('Produtos')
                    ->getStateUsing(function ($record) {
                        return $record->products->pluck('name')->join(', ');
                    })
                    ->limit(50), 
                Tables\Columns\TextColumn::make('sale_value')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => 'R$ ' . number_format($state, 2, ',', '.'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('pending')
                    ->label('R$ pending')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => 'R$ ' . number_format($state, 2, ',', '.'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('profit')
                    ->getStateUsing(fn ($record) => 'R$ ' . number_format($record->profit(), 2, ',', '.')),
                Tables\Columns\TextColumn::make('sale_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            TotalProfitValue::class,
        ];
    } 
}
