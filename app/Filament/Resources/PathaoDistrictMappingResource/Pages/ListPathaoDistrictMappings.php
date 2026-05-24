<?php

declare(strict_types=1);

namespace App\Filament\Resources\PathaoDistrictMappingResource\Pages;

use App\Filament\Resources\PathaoDistrictMappingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPathaoDistrictMappings extends ListRecords
{
    protected static string $resource = PathaoDistrictMappingResource::class;

    protected function getHeaderActions(): array
    {
        return array_merge(
            PathaoDistrictMappingResource::getHeaderActions(),
            [Actions\CreateAction::make()],
        );
    }
}
