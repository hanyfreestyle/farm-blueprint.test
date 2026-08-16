<?php

namespace App\Http\Controllers\Questionnaire;

use App\Http\Controllers\Controller;
use App\Http\Requests\Questionnaire\SaveQuestionnaireAnswerRequest;
use App\Http\Requests\Questionnaire\SubmitQuestionnaireAnswerStepRequest;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionnaireFrontendService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class QuestionnaireAnswerController extends Controller
{
    public function __construct(
        private readonly QuestionnaireFrontendService $frontendService,
    ) {
    }

    public function store(SaveQuestionnaireAnswerRequest $request, QuestionnaireQuestion $question): JsonResponse
    {
        $answer = $this->frontendService->saveAnswer(
            $question,
            $request->input('value'),
            $request->string('notes')->toString(),
        );

        return response()->json([
            'saved' => true,
            'answer_id' => $answer->id,
            'needs_review' => $answer->needs_review,
        ]);
    }

    public function continue(SubmitQuestionnaireAnswerStepRequest $request, QuestionnaireQuestion $question): RedirectResponse
    {
        $mainSectionId = (int) $request->integer('main_section_id');
        $subsectionId = (int) $request->integer('subsection_id');
        $filter = $this->frontendService->normalizeQuestionFilter($request->input('filter'));
        $context = $this->frontendService->getSubsectionStepContext($mainSectionId, $subsectionId, $question->id, $filter);

        if (! $this->frontendService->isValidAnswerForContinuation($question, $request->input('value'))) {
            return back()
                ->withErrors(['value' => 'هذا السؤال مطلوب قبل المتابعة.'])
                ->withInput();
        }

        $this->frontendService->saveAnswer(
            $question,
            $request->input('value'),
            $request->string('notes')->toString(),
        );

        $refreshedContext = $this->frontendService->getSubsectionStepContext($mainSectionId, $subsectionId, $question->id, $filter);

        if ($refreshedContext['nextQuestion'] instanceof QuestionnaireQuestion) {
            return redirect()->route('study.question', [
                'mainSection' => $refreshedContext['mainSection'],
                'subsection' => $refreshedContext['subsection'],
                'question' => $refreshedContext['nextQuestion'],
            ] + ($filter !== QuestionnaireFrontendService::QUESTION_FILTER_ALL ? ['filter' => $filter] : []));
        }

        return redirect()->route('study.subsection.complete', [
            'mainSection' => $refreshedContext['mainSection'],
            'subsection' => $refreshedContext['subsection'],
        ] + ($filter !== QuestionnaireFrontendService::QUESTION_FILTER_ALL ? ['filter' => $filter] : []));
    }

    public function skip(
        Request $request,
        QuestionnaireSection $mainSection,
        QuestionnaireSection $subsection,
        QuestionnaireQuestion $question,
    ): RedirectResponse {
        $filter = $this->frontendService->normalizeQuestionFilter($request->input('filter', $request->query('filter')));
        $context = $this->frontendService->getSubsectionStepContext($mainSection->id, $subsection->id, $question->id, $filter);

        if ($context['nextQuestion'] instanceof QuestionnaireQuestion) {
            return redirect()->route('study.question', [
                'mainSection' => $context['mainSection'],
                'subsection' => $context['subsection'],
                'question' => $context['nextQuestion'],
            ] + ($filter !== QuestionnaireFrontendService::QUESTION_FILTER_ALL ? ['filter' => $filter] : []));
        }

        return redirect()->route('study.subsection.complete', [
            'mainSection' => $context['mainSection'],
            'subsection' => $context['subsection'],
        ] + ($filter !== QuestionnaireFrontendService::QUESTION_FILTER_ALL ? ['filter' => $filter] : []));
    }

    public function destroy(
        Request $request,
        QuestionnaireSection $mainSection,
        QuestionnaireSection $subsection,
        QuestionnaireQuestion $question,
    ): RedirectResponse {
        $filter = $this->frontendService->normalizeQuestionFilter($request->input('filter', $request->query('filter')));

        $this->frontendService->deleteAnswer($question);

        if ($filter === QuestionnaireFrontendService::QUESTION_FILTER_ANSWERED) {
            return redirect()->route('study.subsection', [
                'mainSection' => $mainSection,
                'subsection' => $subsection,
                'filter' => $filter,
            ]);
        }

        return redirect()->route('study.question', [
            'mainSection' => $mainSection,
            'subsection' => $subsection,
            'question' => $question,
        ] + ($filter !== QuestionnaireFrontendService::QUESTION_FILTER_ALL ? ['filter' => $filter] : []));
    }

    public function destroyAll(): RedirectResponse
    {
        $this->frontendService->deleteAllAnswers();

        return redirect()->route('home');
    }
}
