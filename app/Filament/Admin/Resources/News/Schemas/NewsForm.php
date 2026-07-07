<?php

namespace App\Filament\Admin\Resources\News\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('content')
                    ->required(),
            ]);
    }
}
