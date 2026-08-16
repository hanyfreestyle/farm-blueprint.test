<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\QuestionnaireQuestion;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionnaireQuestionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:QuestionnaireQuestion');
    }

    public function view(AuthUser $authUser, QuestionnaireQuestion $questionnaireQuestion): bool
    {
        return $authUser->can('View:QuestionnaireQuestion');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:QuestionnaireQuestion');
    }

    public function update(AuthUser $authUser, QuestionnaireQuestion $questionnaireQuestion): bool
    {
        return $authUser->can('Update:QuestionnaireQuestion');
    }

    public function delete(AuthUser $authUser, QuestionnaireQuestion $questionnaireQuestion): bool
    {
        return $authUser->can('Delete:QuestionnaireQuestion');
    }

    public function restore(AuthUser $authUser, QuestionnaireQuestion $questionnaireQuestion): bool
    {
        return $authUser->can('Restore:QuestionnaireQuestion');
    }

    public function forceDelete(AuthUser $authUser, QuestionnaireQuestion $questionnaireQuestion): bool
    {
        return $authUser->can('ForceDelete:QuestionnaireQuestion');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:QuestionnaireQuestion');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:QuestionnaireQuestion');
    }

    public function replicate(AuthUser $authUser, QuestionnaireQuestion $questionnaireQuestion): bool
    {
        return $authUser->can('Replicate:QuestionnaireQuestion');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:QuestionnaireQuestion');
    }

}