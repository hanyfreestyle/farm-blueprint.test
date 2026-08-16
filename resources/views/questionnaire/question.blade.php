@extends('layouts.questionnaire')

@section('title', $subsection->name)

@section('content')
    <div class="questionnaire-app">
        <div class="shell shell-question">
            <main class="study-main">
                <header class="study-header-card">
                    <div class="light-breadcrumb">
                        <a href="{{ route('home') }}">الرئيسية</a>
                        <span>/</span>
                        <a href="{{ route('study.main-section', $mainSection) }}">{{ $mainSection->name }}</a>
                        <span>/</span>
                        <a href="{{ route('study.subsection', ['mainSection' => $mainSection, 'subsection' => $subsection]) }}">{{ $subsection->name }}</a>
                    </div>

                    <h1 class="study-title">{{ $subsection->name }}</h1>

                    @if (filled($subsection->description))
                        <div class="study-description markdown-content">{!! \Illuminate\Support\Str::markdown($subsection->description) !!}</div>
                    @endif

                    <div class="study-progress-row">
                        <div>
                            <div class="study-progress-label">السؤال {{ $sequencePosition }} من {{ $applicableCount }}</div>
                            <div class="study-progress-meta">{{ $progressSummary['percentage'] ?? 0 }}%</div>
                        </div>
                        <div class="study-progress-percentage">{{ $sequencePosition }} / {{ $applicableCount }}</div>
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
                    :previous-question="$previousQuestion"
                />
            </main>
        </div>
    </div>
@endsection
