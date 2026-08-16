@extends('layouts.questionnaire')

@section('title', 'التقرير الفني')

@section('content')
    <div class="questionnaire-app">
        <div class="shell shell-home">
            <header class="home-header">
                <div class="home-header-row">
                    <div class="page-heading">
                        <div class="page-kicker">المعاينة الحالية</div>
                        <h1>التقرير الفني الرئيسي</h1>
                        <p>هذه المعاينة تعرض نفس محتوى ملف Markdown الذي سيتم تنزيله دون حفظ نسخة مستقلة في قاعدة البيانات.</p>
                    </div>

                    <div class="home-actions">
                        <a class="btn btn-questionnaire-secondary" href="{{ route('home') }}">
                            <i class="fa-solid fa-house"></i>
                            <span>العودة للرئيسية</span>
                        </a>
                        <a class="btn btn-questionnaire-primary" href="{{ route('technical-report.download') }}">
                            <i class="fa-solid fa-download"></i>
                            <span>تحميل التقرير MD</span>
                        </a>
                    </div>
                </div>
            </header>

            <section class="overall-progress-card">
                <div class="overall-progress-head">
                    <div>
                        <div class="overall-progress-title">حالة التقرير الحالية</div>
                        <div class="overall-progress-meta">
                            {{ $reportData['stats']['answered_questions'] }} / {{ $reportData['stats']['applicable_questions'] }} سؤال قابل حاليًا
                        </div>
                    </div>
                    <div class="overall-progress-percentage">{{ $reportData['stats']['completion_percentage'] }}%</div>
                </div>
                <div class="progress questionnaire-progress" role="progressbar" aria-valuenow="{{ $reportData['stats']['completion_percentage'] }}" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar" style="width: {{ $reportData['stats']['completion_percentage'] }}%"></div>
                </div>
            </section>

            <section class="home-header technical-report-preview-card">
                <pre class="technical-report-preview">{{ $markdown }}</pre>
            </section>
        </div>
    </div>
@endsection
