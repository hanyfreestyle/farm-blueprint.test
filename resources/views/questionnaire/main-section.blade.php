@extends('layouts.questionnaire')

@section('title', $mainSection->name)

@section('content')
    @php
        $summary = $mainSection->progress_summary;
    @endphp

    <div class="questionnaire-app">
        <div class="shell shell-home">
            <main class="study-main">
                <header class="study-header-card">
                    <div class="light-breadcrumb">
                        <a href="{{ route('home') }}">الرئيسية</a>
                        <span>/</span>
                        <span>{{ $mainSection->name }}</span>
                    </div>

                    <div class="study-context">القسم الرئيسي</div>
                    <h1 class="study-title">{{ $mainSection->name }}</h1>

                    @if (filled($mainSection->description))
                        <div class="study-description markdown-content">{!! \Illuminate\Support\Str::markdown($mainSection->description) !!}</div>
                    @endif

                    <div class="study-progress-row">
                        <div>
                            <div class="study-progress-label">تقدم هذا القسم</div>
                            <div class="study-progress-meta">{{ $summary['answered'] }} / {{ $summary['total'] }} سؤال</div>
                        </div>
                        <div class="study-progress-percentage">{{ $summary['percentage'] ?? 0 }}%</div>
                    </div>

                    <div class="progress questionnaire-progress" role="progressbar" aria-valuenow="{{ $summary['percentage'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar" style="width: {{ $summary['percentage'] ?? 0 }}%"></div>
                    </div>
                </header>

                <section class="study-header-card subsection-overview-list">
                    @foreach ($mainSection->children as $subsection)
                        @php
                            $subSummary = $subsection->progress_summary;
                            $subsectionQuestionCount = (int) ($subsection->question_count ?? ($subSummary['question_count'] ?? 0));
                            $subsectionAnsweredCount = (int) ($subsection->answered_count ?? ($subSummary['answered'] ?? 0));
                            $subsectionPercentage = (int) ($subSummary['percentage'] ?? 0);
                        @endphp

                        <article class="subsection-overview-row">
                            <div class="subsection-overview-main">
                                <div class="subsection-overview-title-line">
                                    @if ($subsectionQuestionCount > 0)
                                        <a class="subsection-overview-link" href="{{ route('study.subsection', ['mainSection' => $mainSection, 'subsection' => $subsection]) }}">
                                            {{ $subsection->name }}
                                        </a>
                                    @else
                                        <span class="subsection-overview-title">{{ $subsection->name }}</span>
                                    @endif

                                    @if ($subSummary['needs_review'])
                                        <span class="review-inline"><i class="fa-solid fa-triangle-exclamation"></i> يحتاج مراجعة</span>
                                    @endif
                                </div>

                                @if (filled($subsection->description))
                                    <div class="study-description markdown-content">{!! \Illuminate\Support\Str::markdown($subsection->description) !!}</div>
                                @endif

                                <div class="subsection-overview-meta">
                                    @if ($subsectionQuestionCount > 0)
                                        <span>{{ $subsectionAnsweredCount }} / {{ $subsectionQuestionCount }} سؤال</span>
                                    @else
                                        <span>0 سؤال</span>
                                    @endif
                                </div>

                                <div class="progress questionnaire-progress subsection-overview-progress" role="progressbar" aria-valuenow="{{ $subsectionPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar" style="width: {{ $subsectionPercentage }}%"></div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </section>
            </main>
        </div>
    </div>
@endsection
