@props([
    'mainSection',
    'subsection',
    'question',
    'sequencePosition',
    'applicableCount',
    'activeFilter' => 'unanswered',
    'filteredCount' => 0,
    'previousQuestion' => null,
    'reviewMode' => false,
])

@php
    $payload = old() ? [
        'text' => old('value', $question->answer_payload['text']),
        'textarea' => old('value', $question->answer_payload['textarea']),
        'number' => old('value', $question->answer_payload['number']),
        'date' => old('value', $question->answer_payload['date']),
        'yes_no' => old('value', $question->answer_payload['yes_no']),
        'option' => old('value', $question->answer_payload['option']),
        'options' => old('value', $question->answer_payload['options']),
        'notes' => old('notes', $question->answer_payload['notes']),
    ] : $question->answer_payload;

    $notesVisible = filled($payload['notes']);
    $questionOrderLabel = match ($activeFilter) {
        'answered' => 'تمت الإجابة - السؤال',
        'review' => 'تحتاج مراجعة - السؤال',
        'unanswered' => 'غير مجاب - السؤال',
    };

    $selectedYesNo = match (true) {
        $payload['yes_no'] === true, $payload['yes_no'] === 1, $payload['yes_no'] === '1', $payload['yes_no'] === 'true' => true,
        $payload['yes_no'] === false, $payload['yes_no'] === 0, $payload['yes_no'] === '0', $payload['yes_no'] === 'false' => false,
        default => null,
    };
@endphp

<section
    class="question-card"
    data-question-block
    data-question-id="{{ $question->id }}"
    data-question-type="{{ $question->type?->value }}"
    data-save-url="{{ route('questionnaire.answers.store', $question) }}"
>
    <form id="question_skip_{{ $question->id }}" method="POST" action="{{ route('study.question.skip', ['mainSection' => $mainSection, 'subsection' => $subsection, 'question' => $question]) }}" class="question-skip-form">
        @csrf
        <input type="hidden" name="filter" value="{{ $activeFilter }}">
    </form>

    <form id="question_delete_{{ $question->id }}" method="POST" action="{{ route('study.question.answer.destroy', ['mainSection' => $mainSection, 'subsection' => $subsection, 'question' => $question]) }}" class="question-delete-form" onsubmit="return confirm('هل تريد حذف الإجابة المحفوظة لهذا السؤال؟');">
        @csrf
        @method('DELETE')
        <input type="hidden" name="filter" value="{{ $activeFilter }}">
    </form>

    <div class="question-order">{{ $questionOrderLabel }} {{ $sequencePosition }} من {{ $filteredCount }}</div>

    <div class="question-header">
        <h2 class="question-title">{{ $question->title }}</h2>
        <div class="question-badges">
            @if ($question->is_required)
                <span class="question-badge">مطلوب</span>
            @endif
            @if ($question->answer?->needs_review && $question->answer?->review_status === \App\Enums\Questionnaire\AnswerReviewStatus::PENDING)
                <span class="question-badge question-badge-warning">يحتاج مراجعة</span>
            @endif
        </div>
    </div>

    @if (filled($question->help_text))
        <p class="question-help">{{ $question->help_text }}</p>
    @endif

    <form method="POST" action="{{ route('questionnaire.answers.continue', $question) }}" class="question-step-form">
        @csrf
        <input type="hidden" name="main_section_id" value="{{ $mainSection->id }}">
        <input type="hidden" name="subsection_id" value="{{ $subsection->id }}">
        <input type="hidden" name="filter" value="{{ $activeFilter }}">

        <div class="question-answer-area">
            @if ($question->type === \App\Enums\Questionnaire\QuestionType::YES_NO)
                <div class="row g-2 choice-grid">
                    @foreach (['1' => 'نعم', '0' => 'لا'] as $optionValue => $optionLabel)
                        <div class="col-12 col-md-6">
                            <label class="choice-row">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="value"
                                    value="{{ $optionValue }}"
                                    {{ $selectedYesNo === ((string) $optionValue === '1') ? 'checked' : '' }}
                                    data-answer-input
                                >
                                <span>{{ $optionLabel }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            @elseif ($question->type === \App\Enums\Questionnaire\QuestionType::SINGLE_CHOICE)
                <div class="row g-2 choice-grid">
                    @foreach ($question->options as $option)
                        <div class="col-12 col-md-6">
                            <label class="choice-row">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="value"
                                    value="{{ $option->value }}"
                                    {{ $payload['option'] === $option->value ? 'checked' : '' }}
                                    data-answer-input
                                >
                                <span>{{ $option->label }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            @elseif ($question->type === \App\Enums\Questionnaire\QuestionType::MULTI_CHOICE)
                <div class="row g-2 choice-grid">
                    @foreach ($question->options as $option)
                        <div class="col-12 col-md-6">
                            <label class="choice-row">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="value[]"
                                    value="{{ $option->value }}"
                                    {{ in_array($option->value, is_array($payload['options']) ? $payload['options'] : [], true) ? 'checked' : '' }}
                                    data-answer-input
                                >
                                <span>{{ $option->label }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            @elseif ($question->type === \App\Enums\Questionnaire\QuestionType::SELECT)
                <select class="form-select questionnaire-control" name="value" data-answer-input>
                    <option value="">اختر من القائمة</option>
                    @foreach ($question->options as $option)
                        <option value="{{ $option->value }}" {{ $payload['option'] === $option->value ? 'selected' : '' }}>
                            {{ $option->label }}
                        </option>
                    @endforeach
                </select>
            @elseif ($question->type === \App\Enums\Questionnaire\QuestionType::TEXTAREA)
                <textarea class="form-control questionnaire-control questionnaire-textarea" name="value" rows="4" data-answer-input>{{ $payload['textarea'] }}</textarea>
            @elseif ($question->type === \App\Enums\Questionnaire\QuestionType::NUMBER)
                <input class="form-control questionnaire-control" type="number" name="value" value="{{ $payload['number'] }}" data-answer-input>
            @elseif ($question->type === \App\Enums\Questionnaire\QuestionType::DATE)
                <input class="form-control questionnaire-control" type="date" name="value" value="{{ $payload['date'] }}" data-answer-input>
            @else
                <input class="form-control questionnaire-control" type="text" name="value" value="{{ $payload['text'] }}" data-answer-input>
            @endif

            @error('value')
                <div class="question-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="question-notes">
            <button type="button" class="question-notes-toggle" data-notes-toggle>
                <i class="fa-solid fa-plus"></i>
                <span>إضافة ملاحظة</span>
            </button>

            <div class="question-notes-panel {{ $notesVisible ? 'is-open' : '' }}" data-notes-panel>
                <label class="form-label question-notes-label" for="notes_{{ $question->id }}">ملاحظات إضافية</label>
                <textarea id="notes_{{ $question->id }}" class="form-control questionnaire-control questionnaire-notes-textarea" name="notes" rows="3" data-notes-input>{{ $payload['notes'] }}</textarea>
            </div>
        </div>

        <div class="question-step-actions">
            <div class="question-step-actions-group">
                @if ($question->answer)
                    <button type="submit" form="question_delete_{{ $question->id }}" class="btn btn-questionnaire-danger-soft">
                        <i class="fa-solid fa-trash-can"></i>
                        <span>حذف الإجابة</span>
                    </button>
                @endif

                @unless($reviewMode)
                    <button type="submit" form="question_skip_{{ $question->id }}" class="btn btn-questionnaire-secondary">
                        <span>تخطي السؤال</span>
                    </button>
                @endunless
            </div>

            <div class="question-step-actions-group">
                @if ($reviewMode)
                    <a href="{{ route('study.subsection', ['mainSection' => $mainSection, 'subsection' => $subsection, 'filter' => $activeFilter]) }}" class="btn btn-questionnaire-secondary">
                        <i class="fa-solid fa-arrow-right"></i>
                        <span>العودة إلى الإجابات</span>
                    </a>
                @elseif ($previousQuestion)
                    <a
                        href="{{ route('study.question', ['mainSection' => $mainSection, 'subsection' => $subsection, 'question' => $previousQuestion, 'filter' => $activeFilter]) }}"
                        class="btn btn-questionnaire-secondary"
                    >
                        <i class="fa-solid fa-arrow-right"></i>
                        <span>السابق</span>
                    </a>
                @else
                    <a href="{{ route('study.main-section', $mainSection) }}" class="btn btn-questionnaire-secondary">
                        <i class="fa-solid fa-arrow-right"></i>
                        <span>السابق</span>
                    </a>
                @endif

                <button type="submit" class="btn btn-questionnaire-primary">
                    <span>{{ $reviewMode ? 'حفظ التعديل' : 'حفظ ومتابعة' }}</span>
                    <i class="fa-solid {{ $reviewMode ? 'fa-floppy-disk' : 'fa-arrow-left' }}"></i>
                </button>
            </div>
        </div>
    </form>
</section>
