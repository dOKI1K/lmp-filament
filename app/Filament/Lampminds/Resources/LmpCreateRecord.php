<?php

namespace App\Filament\Lampminds\Resources;

use Filament\Resources\Pages\CreateRecord;

class LmpCreateRecord extends CreateRecord
{
    // redirects to index list after creating a new record
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    /**
     * Get the form fields.
     *
     * @return array
     */
    public function fields()
    {
        return [
            // Define your custom form fields here
            //            TextInput::make('Name'),
            //            TextInput::make('Email'),
            // Add more fields as needed
        ];
    }
}
