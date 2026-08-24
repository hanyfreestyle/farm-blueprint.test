<?php

namespace Database\Seeders\Questions\MasterData;

use Database\Seeders\Questions\Concerns\ReferenceMasterDataQuestionsSeeder;

class CoolingSystemsQuestionsSeeder extends ReferenceMasterDataQuestionsSeeder
{
    protected function sectionName(): string
    {
        return 'أنظمة التبريد';
    }

    protected function singularLabel(): string
    {
        return 'نظام التبريد';
    }

    protected function pluralLabel(): string
    {
        return 'أنظمة التبريد';
    }

    protected function seedKeyPrefix(): string
    {
        return 'cooling_system';
    }

    protected function targetEntity(): string
    {
        return 'cooling_system';
    }

    protected function initialValues(): array
    {
        return [
            ['label' => 'التبريد التبخيري بالخلايا المبللة والمراوح', 'value' => 'evaporative_pad_fan'],
            ['label' => 'التبريد الميكانيكي / التكييف', 'value' => 'mechanical_refrigeration_air_conditioning'],
            ['label' => 'التبريد المركزي من خلال وحدة معالجة الهواء', 'value' => 'central_ahu_cooling'],
        ];
    }
}
