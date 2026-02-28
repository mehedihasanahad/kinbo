<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    protected function afterSave(): void
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
