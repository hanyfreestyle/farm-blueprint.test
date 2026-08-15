@extends('layouts.questionnaire')

@section('title', $mainSection->name)

@section('content')
    @php
        $summary = $mainSection->progress_summary;
    @endphp

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

                <section class="study-header-card subsection-overview-list">
                    @foreach ($mainSection->children as $subsection)
                        @php
                            $subSummary = $subsection->progress_summary;
                            $subsectionQuestionCount = (int) ($subsection->question_count ?? ($subSummary['question_count'] ?? 0));
                            $subsectionAnsweredCount = (int) ($subsection->answered_count ?? ($subSummary['answered'] ?? 0));
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

                                <div class="subsection-overview-meta">
                                    @if ($subsectionQuestionCount > 0)
                                        <span>{{ $subsectionAnsweredCount }} / {{ $subsectionQuestionCount }} سؤال</span>
                                    @else
                                        <span>0 سؤال</span>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </section>
            </main>
        </div>
    </div>
@endsection
