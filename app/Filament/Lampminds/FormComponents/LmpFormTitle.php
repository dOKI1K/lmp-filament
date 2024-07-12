<?php

namespace App\Filament\Lampminds\FormComponents;

use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class LmpFormTitle
{
    static function make(string $label = 'Title', string $name = ''): TextInput
    {
        return TextInput::make($name == '' ? Str::snake($label) : $name)
            ->label(__($label))
            ->required();
    }
}
