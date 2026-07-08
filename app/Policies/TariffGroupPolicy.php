<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TariffGroup;
use Illuminate\Auth\Access\HandlesAuthorization;

class TariffGroupPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TariffGroup');
    }

    public function view(AuthUser $authUser, TariffGroup $tariffGroup): bool
    {
        return $authUser->can('View:TariffGroup');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TariffGroup');
    }

    public function update(AuthUser $authUser, TariffGroup $tariffGroup): bool
    {
        return $authUser->can('Update:TariffGroup');
    }

    public function delete(AuthUser $authUser, TariffGroup $tariffGroup): bool
    {
        return $authUser->can('Delete:TariffGroup');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:TariffGroup');
    }

    public function restore(AuthUser $authUser, TariffGroup $tariffGroup): bool
    {
        return $authUser->can('Restore:TariffGroup');
    }

    public function forceDelete(AuthUser $authUser, TariffGroup $tariffGroup): bool
    {
        return $authUser->can('ForceDelete:TariffGroup');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TariffGroup');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TariffGroup');
    }

    public function replicate(AuthUser $authUser, TariffGroup $tariffGroup): bool
    {
        return $authUser->can('Replicate:TariffGroup');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TariffGroup');
    }

}