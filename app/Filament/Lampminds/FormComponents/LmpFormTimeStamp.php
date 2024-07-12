<?php

namespace App\Filament\Lampminds\FormComponents;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class LmpFormTimeStamp
{
    static function make(string $label, string $name = ''): TextInput
    {
        $name = $name ?: Str::snake(Str::lower($label));
        return TextInput::make($name)
            ->label(__($label))
            ->readOnly()
            ->formatStateUsing(fn ($state, $record) =>
            isset($record->$name)
                ? $record->$name->diffForHumans() .
                $record->$name->format('M d, Y h:ia') . ']'
                : 'N/A')
            ->prefixIcon('heroicon-s-clock')
            ->hiddenOn(['create']);
    }
}
