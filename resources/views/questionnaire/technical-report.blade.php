@extends('layouts.questionnaire')

@section('title', 'التقرير الفني')

@push('head')
    <link href="{{ asset('css/technical-report.css') }}" rel="stylesheet">
@endpush

@section('content')
    @php
        $renderedMarkdown = \Illuminate\Support\Str::markdown($markdown);
    @endphp

    <div class="questionnaire-app technical-report-page">
        <div class="shell shell-home">
            <header class="home-header technical-report-hero">
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

            <section class="overall-progress-card technical-report-summary">
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

            <section class="home-header technical-report-preview-card technical-report-sheet">
                <div class="technical-report-preview markdown-content">
                    {!! $renderedMarkdown !!}
                </div>
            </section>
        </div>
    </div>
@endsection
