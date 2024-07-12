<?php

namespace App\Filament\Lampminds\FormComponents;

use Filament\Forms\Components\TextInput;

class LmpFormCreatedByStamp
{
    static function make(string $label = 'Created'): TextInput
    {
        return TextInput::make('created_by_nickname')
            ->label(__($label))
            ->readOnly()
            ->formatStateUsing(fn ($state, $record) =>
            isset($record->created_at)
                ? $record->created_at->diffForHumans() .
                ' by ' . $record->created_by_nickname . ' [' .
                $record->created_at->format('M d, Y ' . formatTime12()) . ']'
                : 'N/A')
            ->prefixIcon('heroicon-s-clock')
            ->suffixIcon('heroicon-o-user')
            ->hiddenOn(['create'])
            ->disabledOn(['edit']);
    }
}
