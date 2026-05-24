<?php

declare(strict_types=1);

namespace App\Filament\Resources\PathaoDistrictMappingResource\Pages;

use App\Filament\Resources\PathaoDistrictMappingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPathaoDistrictMapping extends EditRecord
{
    protected static string $resource = PathaoDistrictMappingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
