<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\QuestionnaireAnswer;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionnaireAnswerPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:QuestionnaireAnswer');
    }

    public function view(AuthUser $authUser, QuestionnaireAnswer $questionnaireAnswer): bool
    {
        return $authUser->can('View:QuestionnaireAnswer');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:QuestionnaireAnswer');
    }

    public function update(AuthUser $authUser, QuestionnaireAnswer $questionnaireAnswer): bool
    {
        return $authUser->can('Update:QuestionnaireAnswer');
    }

    public function delete(AuthUser $authUser, QuestionnaireAnswer $questionnaireAnswer): bool
    {
        return $authUser->can('Delete:QuestionnaireAnswer');
    }

    public function restore(AuthUser $authUser, QuestionnaireAnswer $questionnaireAnswer): bool
    {
        return $authUser->can('Restore:QuestionnaireAnswer');
    }

    public function forceDelete(AuthUser $authUser, QuestionnaireAnswer $questionnaireAnswer): bool
    {
        return $authUser->can('ForceDelete:QuestionnaireAnswer');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:QuestionnaireAnswer');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:QuestionnaireAnswer');
    }

    public function replicate(AuthUser $authUser, QuestionnaireAnswer $questionnaireAnswer): bool
    {
        return $authUser->can('Replicate:QuestionnaireAnswer');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:QuestionnaireAnswer');
    }

}