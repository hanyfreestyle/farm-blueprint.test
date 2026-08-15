<?php

namespace App\Http\Controllers\Questionnaire;

use App\Http\Controllers\Controller;
use App\Http\Requests\Questionnaire\SaveQuestionnaireAnswerRequest;
use App\Models\QuestionnaireQuestion;
use App\Services\Questionnaire\QuestionnaireFrontendService;
use Illuminate\Http\JsonResponse;

class QuestionnaireAnswerController extends Controller
{
    public function __construct(
        private readonly QuestionnaireFrontendService $frontendService,
    ) {
    }

    public function store(SaveQuestionnaireAnswerRequest $request, string $locale, QuestionnaireQuestion $question): JsonResponse
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
}
