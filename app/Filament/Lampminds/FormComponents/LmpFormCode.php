<?php

namespace App\Filament\Lampminds\FormComponents;

use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class LmpFormCode
{
    /**
     * LmpFormCode is a custom form component for Filament that allows to input a code with a mask.
     * The mask format can be found at https://imask.js.org/guide.html#masked
     * It's only for letters and numbers, i.e: '999/aaa', but other special chars are in fixed postitions
     *
     * @param string $mask The mask format
     * @param string $label The label of the input
     * @param string $name The name of the input
     */
    static function make(string $mask, string $label = 'Code', string $name = ''): TextInput
    {
        return TextInput::make($name == '' ? Str::snake($label) : $name)
            ->label(__($label))
            ->mask($mask);
    }
}
