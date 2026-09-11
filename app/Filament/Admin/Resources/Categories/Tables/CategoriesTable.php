<?php

namespace App\Filament\Admin\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('emoji')
                    ->label('アイコン'),
                TextColumn::make('name')
                    ->label('カテゴリ名')
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('スラッグ')
                    ->searchable(),
                TextColumn::make('products_count')
                    ->label('しょうひん数')
                    ->counts('products'),
                TextColumn::make('sort_order')
                    ->label('ならびじゅん')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
