<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Role;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    protected function afterSave(): void
    {
        if (! auth()->user()?->isSuperAdmin()) {
            return;
        }

        $roleId = $this->data['roles'] ?? null;
        $user   = $this->record;
        $role   = $roleId ? Role::find((int) $roleId) : null;

        $role ? $user->syncRoles([$role]) : $user->syncRoles([]);
    }
}
