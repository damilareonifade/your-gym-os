<?php

namespace App\Filament\Resources\Brandings;

use App\Filament\Resources\Brandings\Pages\CreateBranding;
use App\Filament\Resources\Brandings\Pages\EditBranding;
use App\Filament\Resources\Brandings\Pages\ListBrandings;
use App\Filament\Resources\Brandings\Schemas\BrandingForm;
use App\Filament\Resources\Brandings\Tables\BrandingsTable;
use App\Models\Tenant\Branding;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BrandingResource extends Resource
{
    protected static ?string $model = Branding::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static ?string $recordTitleAttribute = 'color';

    public static function getNavigationGroup(): ?string
    {
        return 'Settings';
    }

    public static function form(Schema $schema): Schema
    {
        return BrandingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BrandingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBrandings::route('/'),
            'create' => CreateBranding::route('/create'),
            'edit' => EditBranding::route('/{record}/edit'),
        ];
    }
}
