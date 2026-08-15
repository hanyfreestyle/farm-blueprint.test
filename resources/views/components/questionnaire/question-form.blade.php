@props([
    'mainSection',
    'subsection',
    'question',
    'sequencePosition',
    'applicableCount',
    'previousQuestion' => null,
])

@php($payload = old() ? [
    'text' => old('value', $question->answer_payload['text']),
    'textarea' => old('value', $question->answer_payload['textarea']),
    'number' => old('value', $question->answer_payload['number']),
    'date' => old('value', $question->answer_payload['date']),
    'yes_no' => old('value', $question->answer_payload['yes_no']),
    'option' => old('value', $question->answer_payload['option']),
    'options' => old('value', $question->answer_payload['options']),
    'notes' => old('notes', $question->answer_payload['notes']),
] : $question->answer_payload)
@php($notesVisible = filled($payload['notes']))

<section
    class="question-card"
    data-question-block
    data-question-id="{{ $question->id }}"
    data-question-type="{{ $question->type?->value }}"
    data-save-url="{{ route('questionnaire.answers.store', $question) }}"
>
    <div class="question-order">السؤال {{ $sequencePosition }} من {{ $applicableCount }}</div>

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

        <div class="question-answer-area">
            @if ($question->type === \App\Enums\Questionnaire\QuestionType::YES_NO)
                <div class="choice-list">
                    @foreach (['1' => 'نعم', '0' => 'لا'] as $optionValue => $optionLabel)
                        <label class="choice-row">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="value"
                                value="{{ $optionValue }}"
                                {{ $payload['yes_no'] === $optionValue ? 'checked' : '' }}
                                data-answer-input
                            >
                            <span>{{ $optionLabel }}</span>
                        </label>
                    @endforeach
                </div>
            @elseif ($question->type === \App\Enums\Questionnaire\QuestionType::SINGLE_CHOICE)
                <div class="choice-list">
                    @foreach ($question->options as $option)
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
                    @endforeach
                </div>
            @elseif ($question->type === \App\Enums\Questionnaire\QuestionType::MULTI_CHOICE)
                <div class="choice-list">
                    @foreach ($question->options as $option)
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
            @if ($previousQuestion)
                <a
                    href="{{ route('study.question', ['mainSection' => $mainSection, 'subsection' => $subsection, 'question' => $previousQuestion]) }}"
                    class="btn btn-questionnaire-secondary"
                >
                    السابق
                </a>
            @else
                <a href="{{ route('study.main-section', $mainSection) }}" class="btn btn-questionnaire-secondary">السابق</a>
            @endif

            <button type="submit" class="btn btn-questionnaire-primary">حفظ ومتابعة</button>
        </div>
    </form>
</section>
