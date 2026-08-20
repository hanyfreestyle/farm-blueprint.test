<?php

namespace Database\Seeders\Questions\FarmStructure;

use Database\Seeders\Questions\Concerns\ReferenceReasonQuestionsSeeder;

class ExitReasonsQuestionsSeeder extends ReferenceReasonQuestionsSeeder
{
    protected function sectionName(): string
    {
        return 'أسباب الخروج';
    }

    protected function singularLabel(): string
    {
        return 'سبب الخروج';
    }

    protected function pluralLabel(): string
    {
        return 'أسباب الخروج';
    }

    protected function seedKeyPrefix(): string
    {
        return 'exit_reason';
    }

    protected function targetEntity(): string
    {
        return 'exit_reason';
    }

    protected function initialValues(): array
    {
        return [
            ['label' => 'بيع', 'value' => 'sale'],
            ['label' => 'نفوق', 'value' => 'mortality'],
            ['label' => 'استبعاد نهائي', 'value' => 'final_exclusion'],
            ['label' => 'نقل إلى مزرعة أخرى', 'value' => 'transfer_to_another_farm'],
            ['label' => 'ذبح / استهلاك داخلي', 'value' => 'internal_slaughter_consumption'],
            ['label' => 'فقد', 'value' => 'lost'],
            ['label' => 'سبب آخر', 'value' => 'other'],
        ];
    }
}
