<?php

namespace App\Filament\Concerns;

/**
 * Trait for Filament resources to enforce permission-based access.
 *
 * Each resource declares:
 *   protected static string $viewPermission   = 'view_*';
 *   protected static string $createPermission = 'create_*';
 *   protected static string $editPermission   = 'edit_*';
 *   protected static string $deletePermission = 'delete_*';
 *
 * If a permission property is not declared, the method falls back to
 * requiring view permission (read-only resources) or denies access.
 */
trait HasResourcePermissions
{
    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (! $user) return false;
        if ($user->isSuperAdmin()) return true;

        return $user->hasPermission(static::$viewPermission ?? '');
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        if (! $user) return false;
        if ($user->isSuperAdmin()) return true;

        return $user->hasPermission(static::$createPermission ?? static::$viewPermission ?? '');
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();
        if (! $user) return false;
        if ($user->isSuperAdmin()) return true;

        return $user->hasPermission(static::$editPermission ?? static::$viewPermission ?? '');
    }

    public static function canDelete($record): bool
    {
        $user = auth()->user();
        if (! $user) return false;
        if ($user->isSuperAdmin()) return true;

        return $user->hasPermission(static::$deletePermission ?? '');
    }

    public static function canDeleteAny(): bool
    {
        return static::canDelete(null);
    }

    public static function canView($record): bool
    {
        return static::canAccess();
    }

    public static function canViewAny(): bool
    {
        return static::canAccess();
    }
}
