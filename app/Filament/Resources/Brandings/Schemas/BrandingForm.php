<?php

namespace App\Filament\Resources\Brandings\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;

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

                // Accepted Languages multi-select
                Select::make('accepted_languages')
                    ->label('Accepted Languages')
                    ->options([
                        'en' => 'English',
                        'fr' => 'Français',
                        'es' => 'Español',
                        'de' => 'Deutsch',
                        'es-419' => 'Spanish (Latin America)',
                    ])
                    ->multiple()
                    ->required()
                    ->columnSpanFull()
                    ->dehydrateStateUsing(function ($state) {
                        if (!$state) return null;

                        $options = [
                            'en' => 'English',
                            'fr' => 'Français',
                            'es' => 'Español',
                            'de' => 'Deutsch',
                            'es-419' => 'Spanish (Latin America)',
                        ];

                        return collect($state)->map(function ($code) use ($options) {
                            return [
                                'code' => $code,
                                'name' => $options[$code] ?? $code,
                            ];
                        })->toArray();
                    })
                    ->saveRelationshipsUsing(function ($component, $state) {
                        // Handle saving the transformed data to acceptedLanguages
                        $component->getRecord()->update([
                            'acceptedLanguages' => $state
                        ]);
                    }),

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
}
