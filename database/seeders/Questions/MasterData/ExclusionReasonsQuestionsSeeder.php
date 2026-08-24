<?php

namespace Database\Seeders\Questions\MasterData;

use Database\Seeders\Questions\Concerns\ReferenceReasonQuestionsSeeder;

class ExclusionReasonsQuestionsSeeder extends ReferenceReasonQuestionsSeeder
{
    protected function sectionName(): string
    {
        return 'أسباب الاستبعاد';
    }

    protected function singularLabel(): string
    {
        return 'سبب الاستبعاد';
    }

    protected function pluralLabel(): string
    {
        return 'أسباب الاستبعاد';
    }

    protected function seedKeyPrefix(): string
    {
        return 'exclusion_reason';
    }

    protected function targetEntity(): string
    {
        return 'exclusion_reason';
    }

    protected function initialValues(): array
    {
        return [
            ['label' => 'ضعف النمو', 'value' => 'poor_growth'],
            ['label' => 'وزن غير مناسب', 'value' => 'unsuitable_weight'],
            ['label' => 'مشكلة صحية', 'value' => 'health_problem'],
            ['label' => 'عيب ظاهري', 'value' => 'visible_defect'],
            ['label' => 'مشكلة وراثية', 'value' => 'genetic_problem'],
            ['label' => 'عدم مطابقة مواصفات السلالة', 'value' => 'breed_mismatch'],
            ['label' => 'زيادة العدد عن احتياجات الإحلال', 'value' => 'excess_replacement_needs'],
            ['label' => 'سبب آخر', 'value' => 'other'],
        ];
    }
}
