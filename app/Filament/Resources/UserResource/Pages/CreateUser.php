<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Role;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        if (! auth()->user()?->isSuperAdmin()) {
            return;
        }

        $roleId = $this->data['roles'] ?? null;
        $role   = $roleId ? Role::find((int) $roleId) : null;

        if ($role) {
            $this->record->syncRoles([$role]);
        }
    }
}
