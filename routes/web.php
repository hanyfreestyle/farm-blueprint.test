<?php

use App\Http\Controllers\Questionnaire\QuestionnaireAnswerController;
use App\Http\Controllers\Questionnaire\QuestionnairePageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [QuestionnairePageController::class, 'home'])->name('home');
Route::get('/study/{mainSection}', [QuestionnairePageController::class, 'mainSection'])->name('study.main-section');
Route::get('/study/{mainSection}/{subsection}', [QuestionnairePageController::class, 'subsection'])->name('study.subsection');
Route::get('/study/{mainSection}/{subsection}/questions/{question}', [QuestionnairePageController::class, 'question'])->name('study.question');
Route::get('/study/{mainSection}/{subsection}/completed', [QuestionnairePageController::class, 'completion'])->name('study.subsection.complete');
Route::post('/questionnaire/questions/{question}/answer', [QuestionnaireAnswerController::class, 'store'])->name('questionnaire.answers.store');
Route::post('/questionnaire/questions/{question}/continue', [QuestionnaireAnswerController::class, 'continue'])->name('questionnaire.answers.continue');
