<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MaintenanceBooking;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaintenanceBookingPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MaintenanceBooking');
    }

    public function view(AuthUser $authUser, MaintenanceBooking $maintenanceBooking): bool
    {
        return $authUser->can('View:MaintenanceBooking');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MaintenanceBooking');
    }

    public function update(AuthUser $authUser, MaintenanceBooking $maintenanceBooking): bool
    {
        return $authUser->can('Update:MaintenanceBooking');
    }

    public function delete(AuthUser $authUser, MaintenanceBooking $maintenanceBooking): bool
    {
        return $authUser->can('Delete:MaintenanceBooking');
    }

    public function restore(AuthUser $authUser, MaintenanceBooking $maintenanceBooking): bool
    {
        return $authUser->can('Restore:MaintenanceBooking');
    }

    public function forceDelete(AuthUser $authUser, MaintenanceBooking $maintenanceBooking): bool
    {
        return $authUser->can('ForceDelete:MaintenanceBooking');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MaintenanceBooking');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MaintenanceBooking');
    }

    public function replicate(AuthUser $authUser, MaintenanceBooking $maintenanceBooking): bool
    {
        return $authUser->can('Replicate:MaintenanceBooking');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MaintenanceBooking');
    }

}