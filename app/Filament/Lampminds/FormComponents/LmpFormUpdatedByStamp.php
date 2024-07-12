<?php

namespace App\Filament\Lampminds\FormComponents;

use Filament\Forms\Components\TextInput;

class LmpFormUpdatedByStamp
{
    static function make(string $label = 'Updated'): TextInput
    {
        return TextInput::make('updated_by_nickname')
            ->label(__($label))
            ->readOnly()
            ->formatStateUsing(fn ($state, $record) =>
            isset($record->updated_at)
                ? $record->updated_at->diffForHumans() .
                ' by ' . $record->updated_by_nickname . ' [' .
                $record->updated_at->format('M d, Y ' . formatTime12()) . ']'
                : 'N/A')
            ->prefixIcon('heroicon-s-clock')
            ->suffixIcon('heroicon-o-user')
            ->hiddenOn(['create'])
            ->disabledOn(['edit']);
    }
}
