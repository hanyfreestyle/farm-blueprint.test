<?php

namespace App\Http\Controllers\Questionnaire;

use App\Http\Controllers\Controller;
use App\Http\Requests\Questionnaire\SaveQuestionnaireAnswerRequest;
use App\Http\Requests\Questionnaire\SubmitQuestionnaireAnswerStepRequest;
use App\Models\QuestionnaireQuestion;
use App\Services\Questionnaire\QuestionnaireFrontendService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

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
        $context = $this->frontendService->getSubsectionStepContext($mainSectionId, $subsectionId, $question->id);

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

        $refreshedContext = $this->frontendService->getSubsectionStepContext($mainSectionId, $subsectionId, $question->id);

        if ($refreshedContext['nextQuestion'] instanceof QuestionnaireQuestion) {
            return redirect()->route('study.question', [
                'mainSection' => $refreshedContext['mainSection'],
                'subsection' => $refreshedContext['subsection'],
                'question' => $refreshedContext['nextQuestion'],
            ]);
        }

        return redirect()->route('study.subsection.complete', [
            'mainSection' => $refreshedContext['mainSection'],
            'subsection' => $refreshedContext['subsection'],
        ]);
    }
}
