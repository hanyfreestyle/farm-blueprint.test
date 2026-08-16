@extends('layouts.questionnaire')

@section('title', $subsection->name)

@section('content')
  @php
    $filterLabels = [
        'all' => 'الكل',
        'answered' => 'تمت الإجابة',
        'unanswered' => 'لم تتم الإجابة',
    ];

    $positionLabel = match ($activeFilter) {
        'answered' => 'تمت الإجابة - السؤال',
        'unanswered' => 'غير مجاب - السؤال',
        default => 'السؤال',
    };
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
            <a href="{{ route('study.subsection', ['mainSection' => $mainSection, 'subsection' => $subsection] + ($activeFilter !== 'all' ? ['filter' => $activeFilter] : [])) }}">{{ $subsection->name }}</a>
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
                    href="{{ route('study.subsection', ['mainSection' => $mainSection, 'subsection' => $subsection] + ($filterValue !== 'all' ? ['filter' => $filterValue] : [])) }}"
                    class="btn {{ $activeFilter === $filterValue ? 'btn-questionnaire-primary' : 'btn-questionnaire-secondary' }}"
                >
                  {{ $filterLabel }}
                </a>
              @endforeach
            </div>
          </div>

          <div class="study-progress-row mt-3">
            <div>
              <div class="study-progress-label">{{ $positionLabel }} {{ $sequencePosition }} من {{ $filteredCount }}</div>
              <div class="study-progress-meta">{{ $progressSummary['answered'] }} / {{ $applicableCount }} سؤال</div>
            </div>
            <div class="study-progress-percentage">{{ $progressSummary['percentage'] ?? 0 }}%</div>
          </div>

          <div class="progress questionnaire-progress" role="progressbar" aria-valuenow="{{ $progressSummary['percentage'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar" style="width: {{ $progressSummary['percentage'] ?? 0 }}%"></div>
          </div>
        </header>

        <div class="save-indicator" data-save-indicator>تم الحفظ</div>

        <x-questionnaire.question-form
            :main-section="$mainSection"
            :subsection="$subsection"
            :question="$currentQuestion"
            :sequence-position="$sequencePosition"
            :applicable-count="$applicableCount"
            :active-filter="$activeFilter"
            :filtered-count="$filteredCount"
            :previous-question="$previousQuestion"
        />
      </main>
    </div>
  </div>
@endsection
