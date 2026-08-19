<?php

namespace App\Http\Controllers\Questionnaire;

use App\Http\Controllers\Controller;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionnaireFrontendService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class QuestionnairePageController extends Controller
{
    public function __construct(
        private readonly QuestionnaireFrontendService $frontendService,
    ) {
    }

    public function home(): View
    {
        return view('questionnaire.home', [
            'mainSections' => $this->frontendService->getVisibleMainSectionsTree(),
        ]);
    }

    public function mainSection(QuestionnaireSection $mainSection): View
    {
        abort_unless($mainSection->parent_id === null, 404);

        return view('questionnaire.main-section', [
            'mainSections' => $this->frontendService->getVisibleMainSectionsTree(),
            'mainSection' => $this->frontendService->getVisibleMainSection($mainSection->id),
        ]);
    }

    public function subsection(Request $request, QuestionnaireSection $mainSection, QuestionnaireSection $subsection): View|RedirectResponse
    {
        $filter = $this->frontendService->normalizeQuestionFilter($request->query('filter'));
        $context = $this->frontendService->getSubsectionStepContext($mainSection->id, $subsection->id, null, $filter);

        if ($context['filteredCount'] === 0) {
            return view('questionnaire.empty-filter', [
                'mainSection' => $context['mainSection'],
                'subsection' => $context['subsection'],
                'progressSummary' => $context['progressSummary'],
                'activeFilter' => $context['activeFilter'],
            ]);
        }

        if (in_array($context['activeFilter'], [
            QuestionnaireFrontendService::QUESTION_FILTER_ANSWERED,
            QuestionnaireFrontendService::QUESTION_FILTER_REVIEW,
        ], true)) {
            return view('questionnaire.question', [
                'mainSection' => $context['mainSection'],
                'subsection' => $context['subsection'],
                'currentQuestion' => null,
                'previousQuestion' => null,
                'nextQuestion' => null,
                'progressSummary' => $context['progressSummary'],
                'sequencePosition' => 0,
                'applicableCount' => $context['applicableCount'],
                'activeFilter' => $context['activeFilter'],
                'filteredCount' => $context['filteredCount'],
                'filteredQuestions' => $context['filteredQuestions'],
                'reviewQuestion' => null,
                'isCompleted' => false,
            ]);
        }

        if ($context['currentQuestion'] instanceof QuestionnaireQuestion) {
            return redirect()->route('study.question', [
                'mainSection' => $context['mainSection'],
                'subsection' => $context['subsection'],
                'question' => $context['currentQuestion'],
                'filter' => $filter,
            ]);
        }

        return redirect()->route('study.subsection.complete', [
            'mainSection' => $context['mainSection'],
            'subsection' => $context['subsection'],
            'filter' => $filter,
        ]);
    }

    public function question(
        Request $request,
        QuestionnaireSection $mainSection,
        QuestionnaireSection $subsection,
        QuestionnaireQuestion $question,
    ): View|RedirectResponse {
        $filter = $this->frontendService->normalizeQuestionFilter($request->query('filter'));
        $context = $this->frontendService->getSubsectionStepContext($mainSection->id, $subsection->id, $question->id, $filter);
        $reviewQuestionId = $request->integer('edit');
        $reviewQuestion = $reviewQuestionId > 0
            ? $context['filteredQuestions']->firstWhere('id', $reviewQuestionId)
            : null;

        if ($context['filteredCount'] === 0) {
            return view('questionnaire.empty-filter', [
                'mainSection' => $context['mainSection'],
                'subsection' => $context['subsection'],
                'progressSummary' => $context['progressSummary'],
                'activeFilter' => $context['activeFilter'],
            ]);
        }

        if (in_array($context['activeFilter'], [
            QuestionnaireFrontendService::QUESTION_FILTER_ANSWERED,
            QuestionnaireFrontendService::QUESTION_FILTER_REVIEW,
        ], true) && ! $reviewQuestion instanceof QuestionnaireQuestion) {
            return redirect()->route('study.subsection', [
                'mainSection' => $context['mainSection'],
                'subsection' => $context['subsection'],
                'filter' => $filter,
            ]);
        }

        if (! $context['currentQuestion']?->is($question)) {
            return redirect()->route('study.subsection', [
                'mainSection' => $mainSection,
                'subsection' => $subsection,
                'filter' => $filter,
            ]);
        }

        return view('questionnaire.question', [
            'mainSection' => $context['mainSection'],
            'subsection' => $context['subsection'],
            'currentQuestion' => $context['currentQuestion'],
            'previousQuestion' => $context['previousQuestion'],
            'nextQuestion' => $context['nextQuestion'],
            'progressSummary' => $context['progressSummary'],
            'sequencePosition' => $context['sequencePosition'],
            'applicableCount' => $context['applicableCount'],
            'activeFilter' => $context['activeFilter'],
            'filteredCount' => $context['filteredCount'],
            'filteredQuestions' => $context['filteredQuestions'],
            'reviewQuestion' => $reviewQuestion,
            'isCompleted' => false,
        ]);
    }

    public function completion(Request $request, QuestionnaireSection $mainSection, QuestionnaireSection $subsection): View
    {
        $filter = $this->frontendService->normalizeQuestionFilter($request->query('filter'));
        $context = $this->frontendService->getSubsectionCompletionContext($mainSection->id, $subsection->id, $filter);

        return view('questionnaire.completion', [
            'mainSection' => $context['mainSection'],
            'subsection' => $context['subsection'],
            'progressSummary' => $context['progressSummary'],
            'applicableCount' => $context['applicableCount'],
            'activeFilter' => $context['activeFilter'],
            'nextSubsection' => $this->frontendService->getNextVisibleSubsection($context['mainSection'], $context['subsection']),
        ]);
    }
}
