<?php

declare(strict_types=1);

namespace App\Policies\WebSetting;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\WebSetting\UploadFilter;
use Illuminate\Auth\Access\HandlesAuthorization;

class UploadFilterPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:UploadFilter');
    }

    public function view(AuthUser $authUser, UploadFilter $uploadFilter): bool
    {
        return $authUser->can('View:UploadFilter');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:UploadFilter');
    }

    public function update(AuthUser $authUser, UploadFilter $uploadFilter): bool
    {
        return $authUser->can('Update:UploadFilter');
    }

    public function delete(AuthUser $authUser, UploadFilter $uploadFilter): bool
    {
        return $authUser->can('Delete:UploadFilter');
    }

    public function restore(AuthUser $authUser, UploadFilter $uploadFilter): bool
    {
        return $authUser->can('Restore:UploadFilter');
    }

    public function forceDelete(AuthUser $authUser, UploadFilter $uploadFilter): bool
    {
        return $authUser->can('ForceDelete:UploadFilter');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:UploadFilter');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:UploadFilter');
    }

    public function replicate(AuthUser $authUser, UploadFilter $uploadFilter): bool
    {
        return $authUser->can('Replicate:UploadFilter');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:UploadFilter');
    }

}