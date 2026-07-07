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
                    ->label('タイトル')
                    ->required()
                    ->maxLength(255),

                RichEditor::make('content')
                    ->label('内容')
                    ->required(),
            ]);
    }
}