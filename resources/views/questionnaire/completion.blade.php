@extends('layouts.questionnaire')

@section('title', $subsection->name)

@section('content')
    <div class="questionnaire-app">
        <div class="shell shell-question">
            <main class="study-main">
                <section class="study-header-card completion-card">
                    <div class="light-breadcrumb">
                        <a href="{{ route('home') }}">الرئيسية</a>
                        <span>/</span>
                        <a href="{{ route('study.main-section', $mainSection) }}">{{ $mainSection->name }}</a>
                        <span>/</span>
                        <span>{{ $subsection->name }}</span>
                    </div>

                    <h1 class="study-title">تم الانتهاء من {{ $subsection->name }}</h1>
                    <p class="study-description">يمكنك العودة إلى القسم الرئيسي أو الصفحة الرئيسية، أو متابعة القسم التالي إذا كان متاحًا.</p>

                    <div class="study-progress-row">
                        <div>
                            <div class="study-progress-label">نتيجة هذا القسم</div>
                            <div class="study-progress-meta">{{ $progressSummary['answered'] }} / {{ $applicableCount }} سؤال</div>
                        </div>
                        <div class="study-progress-percentage">{{ $progressSummary['percentage'] ?? 0 }}%</div>
                    </div>

                    <div class="completion-actions">
                        <a href="{{ route('study.main-section', $mainSection) }}" class="btn btn-questionnaire-secondary">العودة إلى القسم الرئيسي</a>
                        <a href="{{ route('home') }}" class="btn btn-questionnaire-secondary">الرئيسية</a>
                        @if ($nextSubsection)
                            <a href="{{ route('study.subsection', ['mainSection' => $mainSection, 'subsection' => $nextSubsection]) }}" class="btn btn-questionnaire-primary">القسم التالي</a>
                        @endif
                    </div>
                </section>
            </main>
        </div>
    </div>
@endsection
