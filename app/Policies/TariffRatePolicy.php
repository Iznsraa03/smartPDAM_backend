<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TariffRate;
use Illuminate\Auth\Access\HandlesAuthorization;

class TariffRatePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TariffRate');
    }

    public function view(AuthUser $authUser, TariffRate $tariffRate): bool
    {
        return $authUser->can('View:TariffRate');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TariffRate');
    }

    public function update(AuthUser $authUser, TariffRate $tariffRate): bool
    {
        return $authUser->can('Update:TariffRate');
    }

    public function delete(AuthUser $authUser, TariffRate $tariffRate): bool
    {
        return $authUser->can('Delete:TariffRate');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:TariffRate');
    }

    public function restore(AuthUser $authUser, TariffRate $tariffRate): bool
    {
        return $authUser->can('Restore:TariffRate');
    }

    public function forceDelete(AuthUser $authUser, TariffRate $tariffRate): bool
    {
        return $authUser->can('ForceDelete:TariffRate');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TariffRate');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TariffRate');
    }

    public function replicate(AuthUser $authUser, TariffRate $tariffRate): bool
    {
        return $authUser->can('Replicate:TariffRate');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TariffRate');
    }

}