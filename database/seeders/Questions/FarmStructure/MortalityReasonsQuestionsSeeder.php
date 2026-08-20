<?php

namespace Database\Seeders\Questions\FarmStructure;

use Database\Seeders\Questions\Concerns\ReferenceReasonQuestionsSeeder;

class MortalityReasonsQuestionsSeeder extends ReferenceReasonQuestionsSeeder
{
    protected function sectionName(): string
    {
        return 'أسباب النفوق';
    }

    protected function singularLabel(): string
    {
        return 'سبب النفوق';
    }

    protected function pluralLabel(): string
    {
        return 'أسباب النفوق';
    }

    protected function seedKeyPrefix(): string
    {
        return 'mortality_reason';
    }

    protected function targetEntity(): string
    {
        return 'mortality_reason';
    }

    protected function initialValues(): array
    {
        return [
            ['label' => 'مرض', 'value' => 'disease'],
            ['label' => 'إصابة', 'value' => 'injury'],
            ['label' => 'مشاكل ولادة', 'value' => 'birth_problems'],
            ['label' => 'سبب غير معروف', 'value' => 'unknown'],
            ['label' => 'حادث', 'value' => 'accident'],
            ['label' => 'سبب آخر', 'value' => 'other'],
        ];
    }
}
