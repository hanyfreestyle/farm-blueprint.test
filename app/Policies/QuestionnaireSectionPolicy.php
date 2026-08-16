<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\QuestionnaireSection;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionnaireSectionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:QuestionnaireSection');
    }

    public function view(AuthUser $authUser, QuestionnaireSection $questionnaireSection): bool
    {
        return $authUser->can('View:QuestionnaireSection');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:QuestionnaireSection');
    }

    public function update(AuthUser $authUser, QuestionnaireSection $questionnaireSection): bool
    {
        return $authUser->can('Update:QuestionnaireSection');
    }

    public function delete(AuthUser $authUser, QuestionnaireSection $questionnaireSection): bool
    {
        return $authUser->can('Delete:QuestionnaireSection');
    }

    public function restore(AuthUser $authUser, QuestionnaireSection $questionnaireSection): bool
    {
        return $authUser->can('Restore:QuestionnaireSection');
    }

    public function forceDelete(AuthUser $authUser, QuestionnaireSection $questionnaireSection): bool
    {
        return $authUser->can('ForceDelete:QuestionnaireSection');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:QuestionnaireSection');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:QuestionnaireSection');
    }

    public function replicate(AuthUser $authUser, QuestionnaireSection $questionnaireSection): bool
    {
        return $authUser->can('Replicate:QuestionnaireSection');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:QuestionnaireSection');
    }

}