@extends('layouts.questionnaire')

@section('title', $subsection->name)

@section('content')
    @php
        $message = match ($activeFilter) {
            'answered' => 'لا توجد أسئلة تمت الإجابة عليها بعد.',
            'review' => 'لا توجد أسئلة تحتاج مراجعة حاليًا.',
            'unanswered' => 'لا توجد أسئلة غير مجابة في هذا القسم.',
            default => 'لا توجد أسئلة متاحة في هذا القسم.',
        };
    @endphp

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

                    <h1 class="study-title">{{ $subsection->name }}</h1>
                    <p class="study-description">{{ $message }}</p>

                    <div class="study-progress-row">
                        <div>
                            <div class="study-progress-label">تقدم هذا القسم</div>
                            <div class="study-progress-meta">{{ $progressSummary['answered'] }} / {{ $progressSummary['total'] }} سؤال</div>
                        </div>
                        <div class="study-progress-percentage">{{ $progressSummary['percentage'] ?? 0 }}%</div>
                    </div>

                    <div class="completion-actions">
                        <a href="{{ route('study.subsection', ['mainSection' => $mainSection, 'subsection' => $subsection, 'filter' => 'unanswered']) }}" class="btn btn-questionnaire-primary">عرض الأسئلة غير المجاب عنها</a>
                        <a href="{{ route('study.subsection', ['mainSection' => $mainSection, 'subsection' => $subsection, 'filter' => 'answered']) }}" class="btn btn-questionnaire-secondary">عرض الإجابات</a>
                        <a href="{{ route('study.subsection', ['mainSection' => $mainSection, 'subsection' => $subsection, 'filter' => 'review']) }}" class="btn btn-questionnaire-secondary">عرض ما يحتاج مراجعة</a>
                        <a href="{{ route('study.main-section', $mainSection) }}" class="btn btn-questionnaire-secondary">العودة إلى القسم الرئيسي</a>
                    </div>
                </section>
            </main>
        </div>
    </div>
@endsection
