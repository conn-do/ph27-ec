<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('category_id')->label('カテゴリー')->relationship('category', 'name')->required(),
                TextInput::make('price')
                    ->required()
                    ->integer()->minValue(0)->maxValue(10000000)
                    ->prefix('¥'),
                TextInput::make('stock')
                    ->required()
                    ->integer()->minValue(0)->maxValue(1000000),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image()
                    ->required()
                    ->disk('public')
                    ->directory('images/products')
                    ->visibility('public'),
            ]);
    }
}
