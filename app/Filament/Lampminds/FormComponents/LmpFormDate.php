<?php

namespace App\Filament\Lampminds\FormComponents;

use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Str;

class LmpFormDate
{
    static function make(string $label = 'Date', string $name = ''): DatePicker
    {
        return DatePicker::make($name == '' ? Str::snake($label) : $name)
            ->label(__($label));
    }
}
