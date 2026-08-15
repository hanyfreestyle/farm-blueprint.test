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
                <section class="study-header-card completion-card">
                    <div class="light-breadcrumb">
                        <a href="{{ route('home') }}">الرئيسية</a>
                        <span>/</span>
                        <a href="{{ route('study.main-section', $mainSection) }}">{{ $mainSection->name }}</a>
                        <span>/</span>
                        <span>{{ $subsection->name }}</span>
                    </div>

                    <div class="study-context">اكتمل هذا القسم</div>
                    <h1 class="study-title">تم الانتهاء من {{ $subsection->name }}.</h1>
                    <p class="study-description">يمكنك العودة إلى القسم الرئيسي أو متابعة القسم التالي إذا كان متاحًا.</p>

                    <div class="study-progress-row">
                        <div>
                            <div class="study-progress-label">نتيجة هذا القسم</div>
                            <div class="study-progress-meta">{{ $progressSummary['answered'] }} / {{ $progressSummary['total'] }} سؤال</div>
                        </div>
                        <div class="study-progress-percentage">{{ $progressSummary['percentage'] ?? 0 }}%</div>
                    </div>

                    <div class="completion-actions">
                        <a href="{{ route('study.main-section', $mainSection) }}" class="btn btn-questionnaire-secondary">العودة إلى القسم</a>
                        @if ($nextSubsection)
                            <a href="{{ route('study.subsection', ['mainSection' => $mainSection, 'subsection' => $nextSubsection]) }}" class="btn btn-questionnaire-primary">القسم التالي</a>
                        @endif
                    </div>
                </section>
            </main>
        </div>
    </div>
@endsection
