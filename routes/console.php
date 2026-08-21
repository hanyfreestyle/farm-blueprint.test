<?php

use App\Services\Questionnaire\QuestionnaireFinalRequirementsInputService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('questionnaire:export-final-requirements', function () {
    /** @var QuestionnaireFinalRequirementsInputService $service */
    $service = app(QuestionnaireFinalRequirementsInputService::class);

    $reportData = $service->buildReportData();
    $markdown = $service->renderMarkdown($reportData);
    $relativePath = 'docs/final-requirements-input.md';
    $absolutePath = base_path($relativePath);

    File::ensureDirectoryExists(dirname($absolutePath));
    File::put($absolutePath, $markdown);

    $this->info("Final Requirements Input generated: {$relativePath}");
    $this->line('Included questions: ' . $reportData['stats']['included_questions']);
    $this->line('Study-only questions excluded: ' . $reportData['stats']['study_only_excluded']);

    if ($reportData['is_ready']) {
        $this->info('Status: READY for Requirements Agent.');
    } else {
        $this->warn('Status: NOT READY — the generated file contains blocking items that still need review.');
        $this->warn('Blocking items: ' . $reportData['stats']['blocking_items']);
    }
})->purpose('Generate docs/final-requirements-input.md from the current approved questionnaire answers');
