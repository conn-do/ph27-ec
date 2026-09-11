<?php

namespace App\Filament\Resources\Coupons\Tables;

use App\Enums\CouponType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CouponsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('クーポンコード')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('割引種別')
                    ->formatStateUsing(fn (CouponType $state) => $state->label()),
                TextColumn::make('value')
                    ->label('割引額 / 割引率')
                    ->formatStateUsing(fn (CouponType $state, $record) => $state === CouponType::Percentage
                        ? $record->value.'%'
                        : number_format($record->value).'円'),
                TextColumn::make('used_count')
                    ->label('利用回数')
                    ->formatStateUsing(fn ($state, $record) => $record->usage_limit
                        ? "{$state} / {$record->usage_limit}"
                        : (string) $state),
                TextColumn::make('expires_at')
                    ->label('有効期限')
                    ->dateTime()
                    ->placeholder('無期限')
                    ->sortable(),
                IconColumn::make('active')
                    ->label('有効')
                    ->boolean(),
            ])
            ->filters([
                //
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
