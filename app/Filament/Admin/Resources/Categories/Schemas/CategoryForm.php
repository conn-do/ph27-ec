<?php

namespace App\Filament\Admin\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('カテゴリ名')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (?string $state, callable $set) => $set('slug', Str::slug((string) $state))),
                TextInput::make('slug')
                    ->label('スラッグ（URLに つかう）')
                    ->required()
                    ->alphaDash()
                    ->unique(ignoreRecord: true),
                TextInput::make('emoji')
                    ->label('アイコン（絵文字）')
                    ->required()
                    ->maxLength(8),
                TextInput::make('sort_order')
                    ->label('ならびじゅん')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
