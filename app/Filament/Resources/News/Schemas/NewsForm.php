<?php

namespace App\Filament\Resources\News\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->maxLength(255)
                    ->required(),
                RichEditor::make('content')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
