<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MaintenanceService;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaintenanceServicePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MaintenanceService');
    }

    public function view(AuthUser $authUser, MaintenanceService $maintenanceService): bool
    {
        return $authUser->can('View:MaintenanceService');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MaintenanceService');
    }

    public function update(AuthUser $authUser, MaintenanceService $maintenanceService): bool
    {
        return $authUser->can('Update:MaintenanceService');
    }

    public function delete(AuthUser $authUser, MaintenanceService $maintenanceService): bool
    {
        return $authUser->can('Delete:MaintenanceService');
    }

    public function restore(AuthUser $authUser, MaintenanceService $maintenanceService): bool
    {
        return $authUser->can('Restore:MaintenanceService');
    }

    public function forceDelete(AuthUser $authUser, MaintenanceService $maintenanceService): bool
    {
        return $authUser->can('ForceDelete:MaintenanceService');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MaintenanceService');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MaintenanceService');
    }

    public function replicate(AuthUser $authUser, MaintenanceService $maintenanceService): bool
    {
        return $authUser->can('Replicate:MaintenanceService');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MaintenanceService');
    }

}