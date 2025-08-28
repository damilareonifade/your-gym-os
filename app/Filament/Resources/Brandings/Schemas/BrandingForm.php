<?php

namespace App\Filament\Resources\Brandings\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BrandingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ColorPicker::make('color'),
                FileUpload::make('brand_logo'),
                FileUpload::make('dark_mode_logo'),
                TextInput::make('favicon'),
                TextInput::make('primary_font'),
                TextInput::make('facebook_social_account'),
                TextInput::make('instagram_social_account'),
            ])
            ->columns(1);
    }
}