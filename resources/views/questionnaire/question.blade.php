@extends('layouts.questionnaire')

@section('title', $subsection->name)

@section('content')
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
                <x-questionnaire.sidebar
                    :main-sections="$mainSections"
                    :current-main-section-id="$mainSection->id"
                    :current-subsection-id="$subsection->id"
                />
            </aside>

            <div class="offcanvas offcanvas-end study-sidebar-offcanvas" tabindex="-1" id="questionnaireSidebar" aria-labelledby="questionnaireSidebarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="questionnaireSidebarLabel">الأقسام</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <x-questionnaire.sidebar
                        :main-sections="$mainSections"
                        :current-main-section-id="$mainSection->id"
                        :current-subsection-id="$subsection->id"
                    />
                </div>
            </div>

            <main class="study-main">
                <header class="study-header-card">
                    <div class="light-breadcrumb">
                        <a href="{{ route('home') }}">الرئيسية</a>
                        <span>/</span>
                        <a href="{{ route('study.main-section', $mainSection) }}">{{ $mainSection->name }}</a>
                        <span>/</span>
                        <span>{{ $subsection->name }}</span>
                    </div>

                    <div class="study-context">{{ $mainSection->name }}</div>
                    <h1 class="study-title">{{ $subsection->name }}</h1>

                    @if (filled($subsection->description))
                        <div class="study-description markdown-content">{!! \Illuminate\Support\Str::markdown($subsection->description) !!}</div>
                    @elseif (filled($mainSection->description))
                        <div class="study-description markdown-content">{!! \Illuminate\Support\Str::markdown($mainSection->description) !!}</div>
                    @endif

                    <div class="study-progress-row">
                        <div>
                            <div class="study-progress-label">التقدم في هذا القسم</div>
                            <div class="study-progress-meta">{{ $progressSummary['answered'] }} / {{ $progressSummary['total'] }} سؤال</div>
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
                    :previous-question="$previousQuestion"
                />
            </main>
        </div>
    </div>
@endsection
