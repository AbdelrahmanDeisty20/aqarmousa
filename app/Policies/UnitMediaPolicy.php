<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\UnitMedia;
use Illuminate\Auth\Access\HandlesAuthorization;

class UnitMediaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:UnitMedia');
    }

    public function view(AuthUser $authUser, UnitMedia $unitMedia): bool
    {
        return $authUser->can('View:UnitMedia');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:UnitMedia');
    }

    public function update(AuthUser $authUser, UnitMedia $unitMedia): bool
    {
        return $authUser->can('Update:UnitMedia');
    }

    public function delete(AuthUser $authUser, UnitMedia $unitMedia): bool
    {
        return $authUser->can('Delete:UnitMedia');
    }

    public function restore(AuthUser $authUser, UnitMedia $unitMedia): bool
    {
        return $authUser->can('Restore:UnitMedia');
    }

    public function forceDelete(AuthUser $authUser, UnitMedia $unitMedia): bool
    {
        return $authUser->can('ForceDelete:UnitMedia');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:UnitMedia');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:UnitMedia');
    }

    public function replicate(AuthUser $authUser, UnitMedia $unitMedia): bool
    {
        return $authUser->can('Replicate:UnitMedia');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:UnitMedia');
    }

}