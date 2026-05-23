<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    protected function afterSave(): void
    {
        $data = $this->form->getRawState();

        $enData = [
            'name'              => $data['translation_name'] ?? '',
            'slug'              => $data['translation_slug'] ?? \Str::slug($data['translation_name'] ?? ''),
            'short_description' => $data['translation_short_description'] ?? null,
            'description'       => $data['translation_description'] ?? null,
        ];

        $this->record->translations()->updateOrCreate(['locale' => 'en'], $enData);
        $this->record->translations()->updateOrCreate(['locale' => 'bn'], $enData);
    }
}
