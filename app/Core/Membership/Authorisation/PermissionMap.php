<?php

namespace App\Core\Membership\Authorisation;

class PermissionMap
{
    /**
     * Maps a Gate/Policy ability string to a Permission enum value (slug).
     * Returns null if there is no mapping defined.
     */
    public static function permissionFor(string $ability): ?string
    {
        $map = [
            'viewAny'  => 'view-any',
            'view'     => 'view',
            'create'   => 'create',
            'update'   => 'update',
            'delete'   => 'delete',
            'restore'  => 'restore',
            'forceDelete' => 'force-delete',
        ];

        return $map[$ability] ?? null;
    }
}
