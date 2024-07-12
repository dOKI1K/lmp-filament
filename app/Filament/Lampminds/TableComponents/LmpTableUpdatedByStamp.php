<?php

namespace App\Filament\Lampminds\TableComponents;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;

class LmpTableUpdatedByStamp
{
    static function make(string $label = 'Updated'): TextColumn
    {
        return TextColumn::make('updated_by_nickname')
            ->label($label)
            ->formatStateUsing(function ($state, $record): HtmlString {
                $user = '<em><b>by ' . $record->updated_by_nickname . '</b></em>';
                if ($record->updated_at) {
                    return new HtmlString($record->updated_at->diffForHumans() . '<br>' .
                        $user . '<br>' .
                        $record->updated_at->format('M d, Y') . '<br>' .
                        $record->updated_at->format(formatTime12()));
                } else {
                    return new HtmlString('(N/A)<br>' . $user);
                }
            })
            ->size(TextColumn\TextColumnSize::ExtraSmall)
            ->icon('heroicon-o-shield-check')
            ->alignment('center')
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
