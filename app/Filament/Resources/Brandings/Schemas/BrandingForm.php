<?php

namespace App\Filament\Resources\Brandings\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TagsInput;

class BrandingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('brand_logo'),
                FileUpload::make('dark_mode_logo'),

                // Default Language dropdown
                Select::make('default_language')
                    ->label('Default Language')
                    ->options([
                        'en' => 'English',
                        'fr' => 'Français',
                        'es' => 'Español',
                    ])
                    ->required(),

                Repeater::make('accepted_languages')
                    ->label('Accepted Languages')
                    ->schema([
                        Select::make('language_code')
                            ->label('Language')
                            ->searchable()
                            ->options(self::getLanguageOptions())
                            ->required()
                    ])
                    ->addActionLabel('Add Language')
                    ->minItems(1)
                    ->maxItems(20)
                    ->columnSpanFull(),

                Repeater::make('colors')
                    ->label('Colors')
                    ->columns(2)
                    ->schema([
                        Select::make('name')
                            ->required()
                            ->options([
                                'primary' => 'Primary',
                                'secondary' => 'Secondary',
                                'accent' => 'Accent',
                                'neutral' => 'Neutral',
                            ])
                            ->placeholder('e.g., Primary, Secondary, Accent'),
                        ColorPicker::make('value')
                            ->required()
                            ->placeholder('#FFFFFF'),
                    ])
                    ->minItems(1)
                    ->maxItems(4)
                    ->columnSpanFull(),

                TextInput::make('favicon'),
                TextInput::make('primary_font'),
                TextInput::make('facebook_social_account'),
                TextInput::make('instagram_social_account'),
            ])
            ->columns(1);
    }



    public static function getLanguageOptions(): array
    {
        return [
            'en' => 'English',
            'es' => 'Spanish (Spain)',
            'fr' => 'French',
            'de' => 'German',
            'it' => 'Italian',
            'pt' => 'Portuguese',
            'ru' => 'Russian',
            'ja' => 'Japanese',
            'ko' => 'Korean',
            'zh-CN' => 'Chinese (Simplified)',
            'zh-TW' => 'Chinese (Traditional)',
            'ar' => 'Arabic',
            'hi' => 'Hindi',
            'th' => 'Thai',
            'vi' => 'Vietnamese',
            'id' => 'Indonesian',
            'ms' => 'Malay',
            'tl' => 'Filipino',
            'sw' => 'Swahili',
            'am' => 'Amharic',
            'yo' => 'Yoruba',
            'ig' => 'Igbo',
            'ha' => 'Hausa',

        ];
    }
}
