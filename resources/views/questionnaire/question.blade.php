@extends('layouts.questionnaire')

@section('title', $subsection->name)

@section('content')
  @php
    $filterLabels = [
        'answered' => 'تمت الإجابة',
        'review' => 'تحتاج مراجعة',
        'unanswered' => 'لم تتم الإجابة',
    ];

    $positionLabel = match ($activeFilter) {
        'answered' => 'تمت الإجابة - السؤال',
        'review' => 'تحتاج مراجعة - السؤال',
        'unanswered' => 'غير مجاب - السؤال',
    };

    $answeredReviewMode = in_array($activeFilter, ['answered', 'review'], true);
  @endphp

  <div class="questionnaire-app">
    <div class="shell shell-question">
      <main class="study-main">
        <header class="study-header-card">
          <div class="light-breadcrumb">
            <a href="{{ route('home') }}">الرئيسية</a>
            <span>/</span>
            <a href="{{ route('study.main-section', $mainSection) }}">{{ $mainSection->name }}</a>
            <span>/</span>
            <a href="{{ route('study.subsection', ['mainSection' => $mainSection, 'subsection' => $subsection, 'filter' => $activeFilter]) }}">{{ $subsection->name }}</a>
          </div>

          <h1 class="study-title">{{ $subsection->name }}</h1>

          @if (filled($subsection->description))
            <div class="study-description markdown-content">{!! \Illuminate\Support\Str::markdown($subsection->description) !!}</div>
          @endif

          <div class="question-filter-bar">
            <span class="question-filter-label">عرض الأسئلة:</span>
            <div class="question-filter-actions">
              @foreach ($filterLabels as $filterValue => $filterLabel)
                <a
                    href="{{ route('study.subsection', ['mainSection' => $mainSection, 'subsection' => $subsection, 'filter' => $filterValue]) }}"
                    class="btn {{ $activeFilter === $filterValue ? 'btn-questionnaire-primary' : 'btn-questionnaire-secondary' }}"
                >
                  {{ $filterLabel }}
                </a>
              @endforeach
            </div>
          </div>

          <div class="study-progress-row mt-3">
            <div>
              <div class="study-progress-label">
                @if ($answeredReviewMode)
                  {{ $activeFilter === 'review' ? 'الأسئلة التي تحتاج مراجعة' : 'الأسئلة التي تمت الإجابة عليها' }}: {{ $filteredCount }}
                @else
                  {{ $positionLabel }} {{ $sequencePosition }} من {{ $filteredCount }}
                @endif
              </div>
              <div class="study-progress-meta">{{ $progressSummary['answered'] }} / {{ $applicableCount }} سؤال</div>
            </div>
            <div class="study-progress-percentage">{{ $progressSummary['percentage'] ?? 0 }}%</div>
          </div>

          <div class="progress questionnaire-progress" role="progressbar" aria-valuenow="{{ $progressSummary['percentage'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar" style="width: {{ $progressSummary['percentage'] ?? 0 }}%"></div>
          </div>
        </header>

        <div class="save-indicator" data-save-indicator>تم الحفظ</div>

        @if ($answeredReviewMode)
          @if ($reviewQuestion)
            <x-questionnaire.question-form
                :main-section="$mainSection"
                :subsection="$subsection"
                :question="$reviewQuestion"
                :sequence-position="$filteredQuestions->search(fn ($item) => $item->id === $reviewQuestion->id) + 1"
                :applicable-count="$applicableCount"
                :active-filter="$activeFilter"
                :filtered-count="$filteredCount"
                :previous-question="null"
                :review-mode="true"
            />
          @else
            <section class="questions-list">
              @foreach ($filteredQuestions as $answeredQuestion)
                <article class="question-card question-review-card">
                  <div class="question-order">تمت الإجابة - السؤال {{ $loop->iteration }} من {{ $filteredCount }}</div>

                  <div class="question-header">
                    <h2 class="question-title">{{ $answeredQuestion->title }}</h2>
                    <div class="question-badges">
                      @if ($answeredQuestion->is_required)
                        <span class="question-badge">مطلوب</span>
                      @endif
                      @if ($answeredQuestion->answer?->needs_review && $answeredQuestion->answer?->review_status === \App\Enums\Questionnaire\AnswerReviewStatus::PENDING)
                        <span class="question-badge question-badge-warning">يحتاج مراجعة</span>
                      @endif
                    </div>
                  </div>

                  @if (filled($answeredQuestion->help_text))
                    <p class="question-help">{{ $answeredQuestion->help_text }}</p>
                  @endif

                  <div class="question-review-answer">
                    <div class="question-review-answer-label">الإجابة الحالية</div>
                    <div class="question-review-answer-value">{{ $answeredQuestion->formatAnswerValue($answeredQuestion->answer?->value) }}</div>
                  </div>

                  @if (filled($answeredQuestion->answer?->notes))
                    <div class="question-review-notes">
                      <div class="question-review-answer-label">ملاحظات</div>
                      <div class="question-review-answer-value">{{ $answeredQuestion->answer?->notes }}</div>
                    </div>
                  @endif

                  <div class="question-review-actions">
                    <a
                        href="{{ route('study.question', ['mainSection' => $mainSection, 'subsection' => $subsection, 'question' => $answeredQuestion, 'filter' => $activeFilter, 'edit' => $answeredQuestion->id]) }}"
                        class="btn btn-questionnaire-secondary"
                    >
                      <i class="fa-solid fa-pen"></i>
                      <span>تعديل</span>
                    </a>
                  </div>
                </article>
              @endforeach
            </section>
          @endif
        @else
          <x-questionnaire.question-form
              :main-section="$mainSection"
              :subsection="$subsection"
              :question="$currentQuestion"
              :sequence-position="$sequencePosition"
              :applicable-count="$applicableCount"
              :active-filter="$activeFilter"
              :filtered-count="$filteredCount"
              :previous-question="$previousQuestion"
              :review-mode="false"
          />
        @endif
      </main>
    </div>
  </div>
@endsection
