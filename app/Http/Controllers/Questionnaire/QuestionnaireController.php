<?php

namespace App\Http\Controllers\Questionnaire;

use App\Http\Controllers\Controller;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionnaireFrontendService;
use Illuminate\Contracts\View\View;

class QuestionnaireController extends Controller
{
    public function __construct(
        private readonly QuestionnaireFrontendService $frontendService,
    ) {
    }

    public function home(string $locale): View
    {
        return view('questionnaire.home', [
            'mainSections' => $this->frontendService->getMainSectionsTree(),
        ]);
    }

    public function study(string $locale, QuestionnaireSection $section): View
    {
        abort_unless($section->parent_id !== null, 404);

        $subsection = $this->frontendService->getStudySubsection($section->id);

        return view('questionnaire.study', [
            'mainSections' => $this->frontendService->getMainSectionsTree(),
            'subsection' => $subsection,
        ]);
    }
}
