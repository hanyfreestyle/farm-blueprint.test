<?php

use App\Http\Controllers\Questionnaire\QuestionnaireAnswerController;
use App\Http\Controllers\Questionnaire\QuestionnairePageController;
use App\Http\Controllers\Questionnaire\QuestionnaireTechnicalReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [QuestionnairePageController::class, 'home'])->name('home');
Route::get('/study/{mainSection}', [QuestionnairePageController::class, 'mainSection'])->name('study.main-section');
Route::get('/study/{mainSection}/{subsection}', [QuestionnairePageController::class, 'subsection'])->name('study.subsection');
Route::get('/study/{mainSection}/{subsection}/questions/{question}', [QuestionnairePageController::class, 'question'])->name('study.question');
Route::get('/study/{mainSection}/{subsection}/completed', [QuestionnairePageController::class, 'completion'])->name('study.subsection.complete');
Route::get('/technical-report', [QuestionnaireTechnicalReportController::class, 'preview'])->name('technical-report.preview');
Route::get('/technical-report/download', [QuestionnaireTechnicalReportController::class, 'download'])->name('technical-report.download');
Route::get('/implementation-prep-report', [QuestionnaireTechnicalReportController::class, 'implementationPrepPreview'])->name('implementation-prep-report.preview');
Route::get('/implementation-prep-report/download', [QuestionnaireTechnicalReportController::class, 'implementationPrepDownload'])->name('implementation-prep-report.download');
Route::get('/final-requirements-input', [QuestionnaireTechnicalReportController::class, 'finalRequirementsInputPreview'])->name('final-requirements-input.preview');
Route::get('/final-requirements-input/download', [QuestionnaireTechnicalReportController::class, 'finalRequirementsInputDownload'])->name('final-requirements-input.download');
Route::post('/questionnaire/questions/{question}/answer', [QuestionnaireAnswerController::class, 'store'])->name('questionnaire.answers.store');
Route::post('/questionnaire/questions/{question}/continue', [QuestionnaireAnswerController::class, 'continue'])->name('questionnaire.answers.continue');
Route::delete('/study/{mainSection}/{subsection}/questions/{question}/answer', [QuestionnaireAnswerController::class, 'destroy'])->name('study.question.answer.destroy');
Route::post('/study/{mainSection}/{subsection}/questions/{question}/skip', [QuestionnaireAnswerController::class, 'skip'])->name('study.question.skip');
Route::delete('/questionnaire/answers', [QuestionnaireAnswerController::class, 'destroyAll'])->name('questionnaire.answers.destroy-all');
