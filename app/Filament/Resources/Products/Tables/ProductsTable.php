<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('しゃしん')
                    ->disk('public'),
                TextColumn::make('name')
                    ->label('しょうひん名')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('カテゴリ')
                    ->badge()
                    ->sortable(),
                TextColumn::make('price')
                    ->label('ほんたい価格')
                    ->numeric()
                    ->suffix(' 円')
                    ->sortable(),
                TextColumn::make('tax_rate')
                    ->label('税率')
                    ->suffix('%')
                    ->sortable(),
                TextColumn::make('stock')
                    ->label('ざいこ')
                    ->numeric()
                    ->sortable()
                    ->color(fn (int $state): string => match (true) {
                        $state === 0 => 'danger',
                        $state <= 3 => 'warning',
                        default => 'success',
                    }),
                IconColumn::make('is_published')
                    ->label('こうかい')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('カテゴリ')
                    ->relationship('category', 'name'),
                TernaryFilter::make('is_published')
                    ->label('こうかいちゅう'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
