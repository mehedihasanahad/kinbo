<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function afterCreate(): void
    {
        $data = $this->form->getRawState();
        $this->record->translations()->updateOrCreate(
            ['locale' => 'en'],
            [
                'name'        => $data['translation_name'] ?? '',
                'slug'        => $data['translation_slug'] ?? \Str::slug($data['translation_name'] ?? ''),
                'description' => $data['translation_description'] ?? null,
            ]
        );
    }
}
