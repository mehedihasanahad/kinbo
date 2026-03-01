<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Role;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        if (! (auth()->user()?->hasPermission('manage_staff') || auth()->user()?->isSuperAdmin())) {
            return;
        }

        $roleId = $this->data['roles'] ?? null;
        if ($roleId) {
            $this->record->roles()->attach($roleId, ['model_type' => $this->record::class]);
        }
    }
}
