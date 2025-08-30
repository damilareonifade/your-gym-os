<?php

namespace App\Filament\Resources\Brandings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class BrandingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                ColorColumn::make('color')
                    ->searchable(),
                ImageColumn::make('brand_logo')
                    ->searchable(),
                TextColumn::make('default_language')
                    ->label('Default Language')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('acceptedLanguages')
                    ->label('Accepted Languages')
                    ->getStateUsing(fn($record) => collect($record->acceptedLanguages)->pluck('name')->toArray())
                    ->badge()
                    ->separator(', '),
                TextColumn::make('facebook_social_account')
                    ->searchable(),
                TextColumn::make('instagram_social_account')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
