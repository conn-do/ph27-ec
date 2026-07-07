<?php

namespace App\Filament\Resources\News\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('title')
                ->label('タイトル')
                ->searchable(),

            TextColumn::make('content')
                ->label('内容')
                ->limit(50),

            TextColumn::make('created_at')
                ->label('作成日')
                ->dateTime(),

            TextColumn::make('updated_at')
                ->label('更新日')
                ->dateTime(),
        ])
            ->filters([
                //
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
