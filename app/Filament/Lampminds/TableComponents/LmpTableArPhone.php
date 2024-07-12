<?php

namespace App\Filament\Lampminds\TableComponents;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;

class LmpTableArPhone
{
    static function make(string $label = 'Phone', string $name = ''): TextColumn
    {
        $name = $name == '' ? Str::snake($label) : $name;
        return TextColumn::make($name)
            ->sortable()
            // ->formatStateUsing(function ($record) use ($name) {
            //     if (getParameterValue('PHONE_MASK', false) != false) {
            //         return formatPhoneMask($record->$name, getParameterValue('PHONE_MASK', false));
            //     }

            //     return $record->$name;

            // })
            ->searchable();
    }
}
