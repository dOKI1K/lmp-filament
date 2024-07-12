<?php

namespace App\Filament\Lampminds\FormComponents;

use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class LmpFormArPhone
{
    /**
     * Create a new North American phone number input.
     * This format is valid for the United States and Canada.
     *
     * @param string $label
     * @param string $name
     * @return \Filament\Forms\Components\TextInput
     */
    static function make(string $label = 'Phone', string $name = ''): TextInput
    {
        return TextInput::make($name == '' ? Str::snake($label) : $name)
            ->label(__($label))
            ->suffixIcon('heroicon-o-phone')
            ->tel()
            ->mask(getParameterValue('PHONE_MASK', false))
            ->placeholder(getParameterValue('PHONE_MASK', '(999) 999-9999'))
            ->maxLength(50);
    }
}
