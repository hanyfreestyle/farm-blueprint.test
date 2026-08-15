<?php

namespace App\Http\Controllers\Questionnaire;

use App\Http\Controllers\Controller;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionnaireFrontendService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

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

    public function subsection(QuestionnaireSection $mainSection, QuestionnaireSection $subsection): RedirectResponse
    {
        $context = $this->frontendService->getSubsectionStepContext($mainSection->id, $subsection->id);

        if ($context['currentQuestion'] instanceof QuestionnaireQuestion) {
            return redirect()->route('study.question', [
                'mainSection' => $context['mainSection'],
                'subsection' => $context['subsection'],
                'question' => $context['currentQuestion'],
            ]);
        }

        return redirect()->route('study.subsection.complete', [
            'mainSection' => $context['mainSection'],
            'subsection' => $context['subsection'],
        ]);
    }

    public function question(
        QuestionnaireSection $mainSection,
        QuestionnaireSection $subsection,
        QuestionnaireQuestion $question,
    ): View {
        $context = $this->frontendService->getSubsectionStepContext($mainSection->id, $subsection->id, $question->id);

        abort_unless($context['currentQuestion']?->is($question), 404);

        return view('questionnaire.question', [
            'mainSections' => $this->frontendService->getVisibleMainSectionsTree(),
            'mainSection' => $context['mainSection'],
            'subsection' => $context['subsection'],
            'currentQuestion' => $context['currentQuestion'],
            'previousQuestion' => $context['previousQuestion'],
            'nextQuestion' => $context['nextQuestion'],
            'progressSummary' => $context['progressSummary'],
            'sequencePosition' => $context['sequencePosition'],
            'applicableCount' => $context['applicableCount'],
            'isCompleted' => false,
        ]);
    }

    public function completion(QuestionnaireSection $mainSection, QuestionnaireSection $subsection): View
    {
        $context = $this->frontendService->getSubsectionStepContext($mainSection->id, $subsection->id);

        abort_if($context['currentQuestion'] instanceof QuestionnaireQuestion, 404);

        return view('questionnaire.completion', [
            'mainSections' => $this->frontendService->getVisibleMainSectionsTree(),
            'mainSection' => $context['mainSection'],
            'subsection' => $context['subsection'],
            'progressSummary' => $context['progressSummary'],
            'nextSubsection' => $this->frontendService->getNextVisibleSubsection($context['mainSection'], $context['subsection']),
        ]);
    }
}
