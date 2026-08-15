@extends('layouts.questionnaire')

@section('title', 'دراسة نظام إدارة مزرعة الأرانب')

@section('content')
    @php
        $totalAnswered = $mainSections->sum(fn ($section) => $section->progress_summary['answered']);
        $totalQuestions = $mainSections->sum(fn ($section) => $section->progress_summary['total']);
        $overallPercentage = $totalQuestions > 0 ? (int) round(($totalAnswered / $totalQuestions) * 100) : 0;
    @endphp

    <div class="questionnaire-app">
        <div class="shell shell-home">
            <header class="home-header">
                <div class="home-header-row">
                    <div class="page-heading">
                        <div class="page-kicker">الواجهة العربية الحالية</div>
                        <h1>دراسة نظام إدارة مزرعة الأرانب</h1>
                        <p>أداة لمراجعة التصور التشغيلي مع المختص وتحويل الإجابات لاحقًا إلى مواصفات تقنية واضحة.</p>
                    </div>

                    <div class="home-actions">
                        <a class="btn btn-questionnaire-secondary" href="#">
                            <i class="fa-solid fa-file-pdf"></i>
                            <span>طباعة التقرير PDF</span>
                        </a>
                    </div>
                </div>
            </header>

            <section class="overall-progress-card">
                <div class="overall-progress-head">
                    <div>
                        <div class="overall-progress-title">تقدم الدراسة الكلي</div>
                        <div class="overall-progress-meta">{{ $totalAnswered }} / {{ $totalQuestions }} سؤال</div>
                    </div>
                    <div class="overall-progress-percentage">{{ $overallPercentage }}%</div>
                </div>
                <div class="progress questionnaire-progress" role="progressbar" aria-valuenow="{{ $overallPercentage }}" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar" style="width: {{ $overallPercentage }}%"></div>
                </div>
            </section>

            <section class="main-sections-grid">
                @foreach ($mainSections as $mainSection)
                    @php($summary = $mainSection->progress_summary)
                    <article class="main-section-card">
                        <div class="main-section-card-head">
                            <div>
                                <div class="main-section-title">{{ $mainSection->name }}</div>
                                @if (filled($mainSection->description))
                                    <div class="main-section-description">
                                        {{ \Illuminate\Support\Str::limit(strip_tags(\Illuminate\Support\Str::markdown($mainSection->description)), 170) }}
                                    </div>
                                @endif
                            </div>
                            <div class="main-section-status {{ $summary['status'] }}">
                                @if ($summary['status'] === 'completed')
                                    <i class="fa-solid fa-circle-check"></i>
                                @elseif ($summary['status'] === 'in_progress')
                                    <i class="fa-solid fa-circle-half-stroke"></i>
                                @else
                                    <i class="fa-regular fa-circle"></i>
                                @endif
                            </div>
                        </div>

                        <div class="main-section-progress-meta">
                            <span>{{ $summary['answered'] }} / {{ $summary['total'] }} سؤال</span>
                            <span>{{ $summary['percentage'] ?? 0 }}%</span>
                            @if ($summary['needs_review'])
                                <span class="review-inline"><i class="fa-solid fa-triangle-exclamation"></i> يحتاج مراجعة</span>
                            @endif
                        </div>

                        <div class="progress questionnaire-progress" role="progressbar" aria-valuenow="{{ $summary['percentage'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar" style="width: {{ $summary['percentage'] ?? 0 }}%"></div>
                        </div>

                        <div class="main-section-actions">
                            <a class="btn btn-questionnaire-primary" href="{{ route('study.main-section', $mainSection) }}">متابعة الدراسة</a>
                        </div>
                    </article>
                @endforeach
            </section>
        </div>
    </div>
@endsection
