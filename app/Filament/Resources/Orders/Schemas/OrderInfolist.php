<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order')
                    ->schema([
                        TextEntry::make('total_price')
                            ->money('JPY'),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ]),

                Section::make('User')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Name'),
                        TextEntry::make('user.email')
                            ->label('Email')
                    ]),

                Section::make('Order Details')
                    ->schema([
                        RepeatableEntry::make('details')
                            ->table([
                                TableColumn::make('Product'),
                                TableColumn::make('Quantity'),
                            ])
                            ->schema([
                                TextEntry::make('product.name'),
                                TextEntry::make('quantity'),
                            ])
                            ->contained(false)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
