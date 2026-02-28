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

        $this->record->translations()->updateOrCreate(
            ['locale' => 'en'],
            [
                'name'              => $data['translation_name'] ?? '',
                'slug'              => $data['translation_slug'] ?? \Str::slug($data['translation_name'] ?? ''),
                'short_description' => $data['translation_short_description'] ?? null,
                'description'       => $data['translation_description'] ?? null,
            ]
        );

        if (! empty($data['bn_name'])) {
            $this->record->translations()->updateOrCreate(
                ['locale' => 'bn'],
                [
                    'name'              => $data['bn_name'],
                    'slug'              => $data['bn_slug'] ?? \Str::slug($data['bn_name']),
                    'short_description' => $data['bn_short_description'] ?? null,
                    'description'       => $data['bn_description'] ?? null,
                ]
            );
        }
    }
}
