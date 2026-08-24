<?php

namespace Database\Seeders\Questions\MasterData;

use Database\Seeders\Questions\Concerns\ReferenceMasterDataQuestionsSeeder;

class VentilationSystemsQuestionsSeeder extends ReferenceMasterDataQuestionsSeeder
{
    protected function sectionName(): string
    {
        return 'أنظمة التهوية';
    }

    protected function singularLabel(): string
    {
        return 'نظام التهوية';
    }

    protected function pluralLabel(): string
    {
        return 'أنظمة التهوية';
    }

    protected function seedKeyPrefix(): string
    {
        return 'ventilation_system';
    }

    protected function targetEntity(): string
    {
        return 'ventilation_system';
    }

    protected function initialValues(): array
    {
        return [
            ['label' => 'التهوية الطبيعية', 'value' => 'natural'],
            ['label' => 'التهوية الميكانيكية بالشفط / الضغط السلبي', 'value' => 'mechanical_exhaust_negative_pressure'],
            ['label' => 'التهوية الميكانيكية بدفع الهواء / الضغط الموجب', 'value' => 'mechanical_supply_positive_pressure'],
            ['label' => 'التهوية الميكانيكية المتوازنة / وحدة معالجة هواء', 'value' => 'balanced_mechanical_ahu'],
            ['label' => 'التهوية المختلطة الطبيعية والميكانيكية', 'value' => 'hybrid_natural_mechanical'],
        ];
    }
}
