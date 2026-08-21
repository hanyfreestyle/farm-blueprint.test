<?php

namespace App\Http\Controllers\Questionnaire;

use App\Http\Controllers\Controller;
use App\Services\Questionnaire\QuestionnaireFinalRequirementsInputService;
use App\Services\Questionnaire\QuestionnaireImplementationPrepReportService;
use App\Services\Questionnaire\QuestionnaireTechnicalReportService;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QuestionnaireTechnicalReportController extends Controller
{
    public function __construct(
        private readonly QuestionnaireTechnicalReportService $technicalReportService,
        private readonly QuestionnaireImplementationPrepReportService $implementationPrepReportService,
        private readonly QuestionnaireFinalRequirementsInputService $finalRequirementsInputService,
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

    public function implementationPrepPreview(): View
    {
        $reportData = $this->implementationPrepReportService->buildReportData();
        $markdown = $this->implementationPrepReportService->renderMarkdown($reportData);

        return view('questionnaire.implementation-prep-report', [
            'reportData' => $reportData,
            'markdown' => $markdown,
        ]);
    }

    public function implementationPrepDownload(): StreamedResponse
    {
        $markdown = $this->implementationPrepReportService->buildReport();

        return response()->streamDownload(
            static function () use ($markdown): void {
                echo $markdown;
            },
            QuestionnaireImplementationPrepReportService::REPORT_FILENAME,
            [
                'Content-Type' => 'text/markdown; charset=UTF-8',
            ],
        );
    }

    public function finalRequirementsInputPreview(): View
    {
        $reportData = $this->finalRequirementsInputService->buildReportData();
        $markdown = $this->finalRequirementsInputService->renderMarkdown($reportData);

        return view('questionnaire.final-requirements-input', [
            'reportData' => $reportData,
            'markdown' => $markdown,
        ]);
    }

    public function finalRequirementsInputDownload(): StreamedResponse
    {
        $markdown = $this->finalRequirementsInputService->buildReport();

        return response()->streamDownload(
            static function () use ($markdown): void {
                echo $markdown;
            },
            QuestionnaireFinalRequirementsInputService::REPORT_FILENAME,
            [
                'Content-Type' => 'text/markdown; charset=UTF-8',
            ],
        );
    }
}
