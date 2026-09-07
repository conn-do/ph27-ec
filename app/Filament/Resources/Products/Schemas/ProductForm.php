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
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
<<<<<<< Updated upstream
                    ->prefix('$'),
                TextInput::make('stock')
                    ->required()
                    ->numeric(),
=======
                    ->prefix('JPY'),
>>>>>>> Stashed changes
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
