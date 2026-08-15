@extends('layouts.questionnaire')

@section('title', $mainSection->name)

@section('content')
    @php($summary = $mainSection->progress_summary)

    <div class="questionnaire-app">
        <div class="shell shell-study">
            <button
                class="btn btn-outline-secondary sidebar-mobile-toggle"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#questionnaireSidebar"
                aria-controls="questionnaireSidebar"
            >
                <i class="fa-solid fa-list-ul"></i>
                <span>الأقسام</span>
            </button>

            <aside class="study-sidebar d-none d-lg-block">
                <x-questionnaire.sidebar :main-sections="$mainSections" :current-main-section-id="$mainSection->id" />
            </aside>

            <div class="offcanvas offcanvas-end study-sidebar-offcanvas" tabindex="-1" id="questionnaireSidebar" aria-labelledby="questionnaireSidebarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="questionnaireSidebarLabel">الأقسام</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <x-questionnaire.sidebar :main-sections="$mainSections" :current-main-section-id="$mainSection->id" />
                </div>
            </div>

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

                <section class="subsection-cards-grid">
                    @foreach ($mainSection->children as $subsection)
                        @php($subSummary = $subsection->progress_summary)
                        <article class="main-section-card subsection-card">
                            <div class="main-section-card-head">
                                <div class="main-section-title">{{ $subsection->name }}</div>
                                @if ($subSummary['needs_review'])
                                    <span class="review-inline"><i class="fa-solid fa-triangle-exclamation"></i> يحتاج مراجعة</span>
                                @endif
                            </div>

                            <div class="main-section-progress-meta">
                                <span>{{ $subSummary['answered'] }} / {{ $subSummary['total'] }} سؤال</span>
                                <span>{{ $subSummary['percentage'] ?? 0 }}%</span>
                            </div>

                            <div class="progress questionnaire-progress" role="progressbar" aria-valuenow="{{ $subSummary['percentage'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar" style="width: {{ $subSummary['percentage'] ?? 0 }}%"></div>
                            </div>

                            <div class="main-section-actions">
                                <a class="btn btn-questionnaire-primary" href="{{ route('study.subsection', ['mainSection' => $mainSection, 'subsection' => $subsection]) }}">متابعة</a>
                            </div>
                        </article>
                    @endforeach
                </section>
            </main>
        </div>
    </div>
@endsection
