<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Filament\Widgets\InventoryOverview;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->maxLength(255),
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->default(1)
                    ->numeric(),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        'encomendado' => 'Encomendado',
                        'em estoque' => 'Em Estoque',
                        'esgotado' => 'Esgotado',
                    ])
                    ->default('em estoque'),
                Forms\Components\Select::make('condition')
                    ->required()
                    ->options([
                        'novo' => 'Novo',
                        'semi-novo' => 'Semi-novo',
                        'usado' => 'Usado',
                    ])
                    ->default('novo'),
                Forms\Components\DatePicker::make('buy_date')
                    ->required()
                    ->default(now()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('value')
                    ->alignment('center')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => 'R$ ' . number_format($state, 2, ',', '.'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->alignment('center')
                    ->colors([
                        'warning' => 'encomendado',
                        'success' => 'em estoque',
                        'danger' => 'esgotado',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'encomendado',
                        'heroicon-o-check-circle' => 'em estoque',
                        'heroicon-o-x-circle' => 'esgotado',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->alignment('center')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('buy_date')
                    ->alignment('center')
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
