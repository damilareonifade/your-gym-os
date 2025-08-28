<?php

namespace App\Filament\Resources\Brandings\Pages;

use App\Filament\Resources\Brandings\BrandingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBranding extends EditRecord
{
    protected static string $resource = BrandingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
