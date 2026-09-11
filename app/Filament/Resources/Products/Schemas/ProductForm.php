<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('しょうひん名')
                    ->required(),
                Select::make('category_id')
                    ->label('カテゴリ')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('price')
                    ->label('ほんたい価格（税ぬき）')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->suffix('円'),
                Select::make('tax_rate')
                    ->label('消費税率')
                    ->required()
                    ->options([
                        10 => '10%（ふつう）',
                        8 => '8%（けいげん税率・たべもの）',
                    ])
                    ->default(10),
                TextInput::make('stock')
                    ->label('ざいこ')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->suffix('こ'),
                Toggle::make('is_published')
                    ->label('おみせに出す')
                    ->default(true),
                Textarea::make('description')
                    ->label('せつめい')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->label('しゃしん')
                    ->image()
                    ->required()
                    ->disk('public')
                    ->directory('images/products')
                    ->visibility('public')
                    ->columnSpanFull(),
            ]);
    }
}
