<?php

namespace App\Filament\Lampminds\FormComponents;

use Filament\Forms\Components\RichEditor;
use Illuminate\Support\Str;

class LmpFormRichEditor
{
    static function make(string $label, string $name = ''): RichEditor
    {
        return RichEditor::make($name == '' ? Str::snake($label) : $name)
            ->toolbarButtons([
                'attachFiles',
                'blockquote',
                'bold',
                'bulletList',
                'h1',
                'h2',
                'h3',
                'italic',
                'link',
                'orderedList',
                'redo',
                'undo',
            ])
            ->label(__($label))
            ->columnSpanFull();
    }
}
