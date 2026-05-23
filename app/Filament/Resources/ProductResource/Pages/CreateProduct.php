<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function afterCreate(): void
    {
        $data = $this->form->getRawState();

        $enData = [
            'name'              => $data['translation_name'] ?? '',
            'slug'              => $data['translation_slug'] ?? \Str::slug($data['translation_name'] ?? ''),
            'short_description' => $data['translation_short_description'] ?? null,
            'description'       => $data['translation_description'] ?? null,
            'meta_title'        => $data['translation_meta_title'] ?? null,
            'meta_description'  => $data['translation_meta_description'] ?? null,
            'meta_keywords'     => $data['translation_meta_keywords'] ?? null,
        ];

        $this->record->translations()->updateOrCreate(['locale' => 'en'], $enData);
        $this->record->translations()->updateOrCreate(['locale' => 'bn'], $enData);
    }
}
