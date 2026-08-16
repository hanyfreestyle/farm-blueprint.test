<?php

namespace App\Http\Controllers\Questionnaire;

use App\Http\Controllers\Controller;
use App\Services\Questionnaire\QuestionnaireTechnicalReportService;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QuestionnaireTechnicalReportController extends Controller
{
    public function __construct(
        private readonly QuestionnaireTechnicalReportService $technicalReportService,
    ) {
    }

    public function preview(): View
    {
        $reportData = $this->technicalReportService->buildReportData();
        $markdown = $this->technicalReportService->renderMarkdown($reportData);

        return view('questionnaire.technical-report', [
            'reportData' => $reportData,
            'markdown' => $markdown,
        ]);
    }

    public function download(): StreamedResponse
    {
        $markdown = $this->technicalReportService->buildReport();

        return response()->streamDownload(
            static function () use ($markdown): void {
                echo $markdown;
            },
            QuestionnaireTechnicalReportService::REPORT_FILENAME,
            [
                'Content-Type' => 'text/markdown; charset=UTF-8',
            ],
        );
    }
}
