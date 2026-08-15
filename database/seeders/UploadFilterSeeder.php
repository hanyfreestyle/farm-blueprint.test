<?php

namespace Database\Seeders;

use App\Models\WebSetting\UploadFilter;
use Illuminate\Database\Seeder;

class UploadFilterSeeder extends Seeder
{
    public function run(): void
    {
        $filter = UploadFilter::query()->updateOrCreate(
            ['name' => 'User Avatar'],
            [
                'type' => 4,
                'convert_state' => true,
                'quality_val' => 90,
                'width' => 1200,
                'height' => 800,
                'canvas_back' => '#ffffff',
                'crop_aspect_ratio' => '3:2',
                'state' => 1,
            ]
        );

        $filter->sizes()->delete();

        $filter->sizes()->create([
            'type' => 4,
            'width' => 300,
            'height' => 200,
            'canvas_back' => '#ffffff',
            'text_state' => false,
            'watermark_state' => false,
        ]);
    }
}
