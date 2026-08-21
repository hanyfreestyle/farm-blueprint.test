<?php

namespace Database\Seeders\Questions\FarmStructure;

use Database\Seeders\Questions\Concerns\ReferenceMasterDataQuestionsSeeder;

class HeatingSystemsQuestionsSeeder extends ReferenceMasterDataQuestionsSeeder
{
    protected function sectionName(): string
    {
        return 'أنظمة التدفئة';
    }

    protected function singularLabel(): string
    {
        return 'نظام التدفئة';
    }

    protected function pluralLabel(): string
    {
        return 'أنظمة التدفئة';
    }

    protected function seedKeyPrefix(): string
    {
        return 'heating_system';
    }

    protected function targetEntity(): string
    {
        return 'heating_system';
    }

    protected function initialValues(): array
    {
        return [
            ['label' => 'التدفئة الكهربائية', 'value' => 'electric'],
            ['label' => 'التدفئة بالغاز', 'value' => 'gas'],
            ['label' => 'التدفئة المركزية بالغلايات والمياه الساخنة', 'value' => 'boiler_hot_water'],
            ['label' => 'التدفئة المركزية من خلال وحدة معالجة الهواء', 'value' => 'central_ahu'],
        ];
    }
}
