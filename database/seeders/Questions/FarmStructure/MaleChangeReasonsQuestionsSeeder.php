<?php

namespace Database\Seeders\Questions\FarmStructure;

use Database\Seeders\Questions\Concerns\ReferenceReasonQuestionsSeeder;

class MaleChangeReasonsQuestionsSeeder extends ReferenceReasonQuestionsSeeder
{
    protected function sectionName(): string
    {
        return 'أسباب تغيير الذكر';
    }

    protected function singularLabel(): string
    {
        return 'سبب تغيير الذكر';
    }

    protected function pluralLabel(): string
    {
        return 'أسباب تغيير الذكر';
    }

    protected function seedKeyPrefix(): string
    {
        return 'male_change_reason';
    }

    protected function targetEntity(): string
    {
        return 'male_change_reason';
    }

    protected function initialValues(): array
    {
        return [
            ['label' => 'انخفاض الخصوبة', 'value' => 'low_fertility'],
            ['label' => 'مشكلة صحية', 'value' => 'health_problem'],
            ['label' => 'عزل', 'value' => 'isolation'],
            ['label' => 'نفوق', 'value' => 'mortality'],
            ['label' => 'بيع / خروج', 'value' => 'sale_exit'],
            ['label' => 'تجنب القرابة', 'value' => 'avoid_inbreeding'],
            ['label' => 'إعادة توزيع القطيع', 'value' => 'herd_redistribution'],
            ['label' => 'راحة الذكر', 'value' => 'male_rest'],
            ['label' => 'تحسين النتائج', 'value' => 'improve_results'],
            ['label' => 'سبب آخر', 'value' => 'other'],
        ];
    }
}
