<?php

namespace App\Filament\Lampminds\FormComponents;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Str;

class LmpFormTextArea
{
    static function make(string $label, string $name = ''): RichEditor
    {
        return RichEditor::make($name == '' ? Str::snake($label) : $name)
            ->label(__($label))
            ->rows(5)
            ->columnSpan(2);
    }
}
