@props([
    'question',
    'index',
])

@php($payload = $question->answer_payload)
@php($type = $question->type?->value)
@php($answer = $question->answer)
@php($notesVisible = filled($payload['notes']))

<section
    class="question-card"
    data-question-block
    data-question-id="{{ $question->id }}"
    data-question-type="{{ $type }}"
    data-save-url="{{ route('questionnaire.answers.store', ['locale' => app()->getLocale(), 'question' => $question]) }}"
>
    <div class="question-order">سؤال {{ $index }}</div>

    <div class="question-header">
        <h2 class="question-title">{{ $question->title }}</h2>
        <div class="question-badges">
            @if ($question->is_required)
                <span class="question-badge">مطلوب</span>
            @endif
            @if ($answer?->needs_review && $answer?->review_status === \App\Enums\Questionnaire\AnswerReviewStatus::PENDING)
                <span class="question-badge question-badge-warning">يحتاج مراجعة</span>
            @endif
        </div>
    </div>

    @if (filled($question->help_text))
        <p class="question-help">{{ $question->help_text }}</p>
    @endif

    <div class="question-answer-area">
        @if ($question->type === \App\Enums\Questionnaire\QuestionType::YES_NO)
            <div class="choice-list">
                @foreach (['1' => 'نعم', '0' => 'لا'] as $optionValue => $optionLabel)
                    <label class="choice-row">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="question_{{ $question->id }}"
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
                            name="question_{{ $question->id }}"
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
                            name="question_{{ $question->id }}[]"
                            value="{{ $option->value }}"
                            {{ in_array($option->value, $payload['options'], true) ? 'checked' : '' }}
                            data-answer-input
                        >
                        <span>{{ $option->label }}</span>
                    </label>
                @endforeach
            </div>
        @elseif ($question->type === \App\Enums\Questionnaire\QuestionType::SELECT)
            <select class="form-select questionnaire-control" data-answer-input>
                <option value="">اختر من القائمة</option>
                @foreach ($question->options as $option)
                    <option value="{{ $option->value }}" {{ $payload['option'] === $option->value ? 'selected' : '' }}>
                        {{ $option->label }}
                    </option>
                @endforeach
            </select>
        @elseif ($question->type === \App\Enums\Questionnaire\QuestionType::TEXTAREA)
            <textarea class="form-control questionnaire-control questionnaire-textarea" rows="4" data-answer-input>{{ $payload['textarea'] }}</textarea>
        @elseif ($question->type === \App\Enums\Questionnaire\QuestionType::NUMBER)
            <input class="form-control questionnaire-control" type="number" value="{{ $payload['number'] }}" data-answer-input>
        @elseif ($question->type === \App\Enums\Questionnaire\QuestionType::DATE)
            <input class="form-control questionnaire-control" type="date" value="{{ $payload['date'] }}" data-answer-input>
        @else
            <input class="form-control questionnaire-control" type="text" value="{{ $payload['text'] }}" data-answer-input>
        @endif
    </div>

    <div class="question-notes">
        <button type="button" class="question-notes-toggle" data-notes-toggle>
            <i class="fa-solid fa-plus"></i>
            <span>إضافة ملاحظة</span>
        </button>

        <div class="question-notes-panel {{ $notesVisible ? 'is-open' : '' }}" data-notes-panel>
            <label class="form-label question-notes-label" for="notes_{{ $question->id }}">ملاحظات إضافية</label>
            <textarea id="notes_{{ $question->id }}" class="form-control questionnaire-control questionnaire-notes-textarea" rows="3" data-notes-input>{{ $payload['notes'] }}</textarea>
        </div>
    </div>
</section>
