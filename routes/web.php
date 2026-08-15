<?php

use App\Http\Controllers\Questionnaire\QuestionnaireAnswerController;
use App\Http\Controllers\Questionnaire\QuestionnaireController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

$supportedLocales = implode('|', array_keys(LaravelLocalization::getSupportedLocales()));

Route::redirect('/', '/'.app()->getLocale());

Route::group([
    'prefix' => '{locale}',
    'where' => ['locale' => $supportedLocales],
    'middleware' => ['localize', 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
], function (): void {
    Route::get('/', [QuestionnaireController::class, 'home'])->name('home');
    Route::get('/study/{section}', [QuestionnaireController::class, 'study'])->name('study.show');
    Route::post('/questionnaire/questions/{question}/answer', [QuestionnaireAnswerController::class, 'store'])
        ->name('questionnaire.answers.store');
});
