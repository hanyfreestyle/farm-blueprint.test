<?php

namespace Database\Seeders\Questions\FarmStructure;

use Database\Seeders\Questions\Concerns\ReferenceReasonQuestionsSeeder;

class TransferReasonsQuestionsSeeder extends ReferenceReasonQuestionsSeeder
{
    protected function sectionName(): string
    {
        return 'أسباب النقل';
    }

    protected function singularLabel(): string
    {
        return 'سبب النقل';
    }

    protected function pluralLabel(): string
    {
        return 'أسباب النقل';
    }

    protected function seedKeyPrefix(): string
    {
        return 'transfer_reason';
    }

    protected function targetEntity(): string
    {
        return 'transfer_reason';
    }

    protected function initialValues(): array
    {
        return [
            ['label' => 'تغيير مكان داخل القطيع', 'value' => 'change_location_within_herd'],
            ['label' => 'الانتقال للفطام', 'value' => 'move_to_weaning'],
            ['label' => 'الانتقال للتسمين', 'value' => 'move_to_fattening'],
            ['label' => 'الانتقال للعزل', 'value' => 'move_to_isolation'],
            ['label' => 'العودة من العزل', 'value' => 'return_from_isolation'],
            ['label' => 'إعادة توزيع القطيع', 'value' => 'herd_redistribution'],
            ['label' => 'صيانة القفص', 'value' => 'cage_maintenance'],
            ['label' => 'نقل بين البطاريات', 'value' => 'between_batteries'],
            ['label' => 'نقل بين العنابر', 'value' => 'between_barns'],
            ['label' => 'نقل بين المزارع', 'value' => 'between_farms'],
            ['label' => 'سبب آخر', 'value' => 'other'],
        ];
    }
}
