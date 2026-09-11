<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('total_price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options([
                        OrderStatus::Pending->value => '処理中',
                        OrderStatus::Shipped->value => '発送済み',
                        OrderStatus::Cancelled->value => 'キャンセル',
                    ])
                    ->required()
                    ->live(),
                TextInput::make('carrier')
                    ->label('配送業者')
                    ->maxLength(255)
                    ->visible(fn (Get $get): bool => $get('status') === OrderStatus::Shipped->value),
                TextInput::make('tracking_number')
                    ->label('追跡番号')
                    ->maxLength(255)
                    ->visible(fn (Get $get): bool => $get('status') === OrderStatus::Shipped->value),
            ]);
    }
}
