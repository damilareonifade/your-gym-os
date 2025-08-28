<?php

namespace App\Filament\Resources\Brandings\Pages;

use App\Filament\Resources\Brandings\BrandingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBrandings extends ListRecords
{
    protected static string $resource = BrandingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
