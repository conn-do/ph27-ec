<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ContactMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('お名前')
                    ->disabled(),
                TextInput::make('email')
                    ->label('メールアドレス')
                    ->disabled(),
                Textarea::make('message')
                    ->label('お問い合わせ内容')
                    ->rows(6)
                    ->disabled()
                    ->columnSpanFull(),
                Toggle::make('is_read')
                    ->label('対応済み'),
            ]);
    }
}
