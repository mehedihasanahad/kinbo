<?php

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
        $this->syncUserRole();
    }

    private function syncUserRole(): void
    {
        if (! (auth()->user()?->hasPermission('manage_staff') || auth()->user()?->isSuperAdmin())) {
            return;
        }

        $roleId = $this->data['roles'] ?? null;
        $user   = $this->record;

        // Detach all admin roles first
        $adminRoleIds = Role::whereIn('name', ['super_admin', 'admin', 'staff'])->pluck('id');
        $user->roles()->detach($adminRoleIds);

        if ($roleId) {
            $user->roles()->attach($roleId, ['model_type' => $user::class]);
        }
    }
}
