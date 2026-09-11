<?php

namespace App\Filament\Resources\Coupons\Schemas;

use App\Enums\CouponType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('クーポンコード')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Select::make('type')
                    ->label('割引種別')
                    ->options([
                        CouponType::Fixed->value => CouponType::Fixed->label(),
                        CouponType::Percentage->value => CouponType::Percentage->label(),
                    ])
                    ->required(),
                TextInput::make('value')
                    ->label('割引額 / 割引率')
                    ->helperText('固定額割引の場合は円額、割合割引の場合は0〜100の数値')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                TextInput::make('usage_limit')
                    ->label('利用可能回数（上限）')
                    ->helperText('空欄の場合は無制限')
                    ->numeric()
                    ->minValue(1),
                TextInput::make('used_count')
                    ->label('利用済み回数')
                    ->numeric()
                    ->default(0)
                    ->required(),
                DateTimePicker::make('expires_at')
                    ->label('有効期限')
                    ->helperText('空欄の場合は無期限'),
                Toggle::make('active')
                    ->label('有効')
                    ->default(true),
            ]);
    }
}
