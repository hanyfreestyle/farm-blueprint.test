<?php

namespace App\Http\Controllers\Questionnaire;

use App\Http\Controllers\Controller;
use App\Services\Questionnaire\QuestionnaireTreeExportService;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Throwable;

class QuestionnaireTreeExportController extends Controller
{
    public function __invoke(QuestionnaireTreeExportService $exportService): RedirectResponse
    {
        $adminPanel = Filament::getPanel('admin');

        abort_unless(
            auth()->check()
            && $adminPanel
            && auth()->user()?->canAccessPanel($adminPanel),
            403,
        );

        try {
            $result = $exportService->export();

            return redirect()
                ->route('home')
                ->with(
                    'questionnaire_export_success',
                    'تم تحديث شجرة الأسئلة والإجابات بنجاح: '
                    . $result['answer_files'] . ' ملف إجابات، '
                    . $result['answered_questions'] . ' إجابة من '
                    . $result['applicable_questions'] . ' سؤال قابل حاليًا. '
                    . 'تم تحديث ' . $result['index_path'] . '.',
                );
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('home')
                ->with(
                    'questionnaire_export_error',
                    'فشل تحديث شجرة الأسئلة والإجابات: ' . $exception->getMessage(),
                );
        }
    }
}
