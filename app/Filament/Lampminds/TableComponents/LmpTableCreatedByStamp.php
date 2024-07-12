<?php

namespace App\Filament\Lampminds\TableComponents;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;

class LmpTableCreatedByStamp
{
    static function make(bool $isToggleable = true, string $label = 'Created'): TextColumn
    {
        return TextColumn::make('created_by_nickname')
            ->label($label)
            ->formatStateUsing(function ($state, $record): HtmlString {
                $user = '<em><b>by ' . $record->created_by_nickname . '</b></em>';
                if ($record->created_at) {
                    return new HtmlString($record->created_at->diffForHumans() . '<br>' .
                        $user . '<br>' .
                        $record->created_at->format('M d, Y') . '<br>' .
                        $record->created_at->format(formatTime12()));
                } else {
                    return new HtmlString('(N/A)<br>' . $user);
                }
            })
            ->size(TextColumn\TextColumnSize::ExtraSmall)
            ->icon('heroicon-o-shield-check')
            ->alignment('center')
            ->toggleable($isToggleable);
    }
}
