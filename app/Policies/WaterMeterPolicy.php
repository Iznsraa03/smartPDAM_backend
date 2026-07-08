<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\WaterMeter;
use Illuminate\Auth\Access\HandlesAuthorization;

class WaterMeterPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:WaterMeter');
    }

    public function view(AuthUser $authUser, WaterMeter $waterMeter): bool
    {
        return $authUser->can('View:WaterMeter');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:WaterMeter');
    }

    public function update(AuthUser $authUser, WaterMeter $waterMeter): bool
    {
        return $authUser->can('Update:WaterMeter');
    }

    public function delete(AuthUser $authUser, WaterMeter $waterMeter): bool
    {
        return $authUser->can('Delete:WaterMeter');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:WaterMeter');
    }

    public function restore(AuthUser $authUser, WaterMeter $waterMeter): bool
    {
        return $authUser->can('Restore:WaterMeter');
    }

    public function forceDelete(AuthUser $authUser, WaterMeter $waterMeter): bool
    {
        return $authUser->can('ForceDelete:WaterMeter');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:WaterMeter');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:WaterMeter');
    }

    public function replicate(AuthUser $authUser, WaterMeter $waterMeter): bool
    {
        return $authUser->can('Replicate:WaterMeter');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:WaterMeter');
    }

}