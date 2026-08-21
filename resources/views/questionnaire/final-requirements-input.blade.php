@extends('layouts.questionnaire')

@section('title', 'ملف الإدخال النهائي للمتطلبات')

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
                        <div class="page-kicker">Final Requirements Input</div>
                        <h1>ملف الإدخال النهائي لكتابة المتطلبات</h1>
                        <p>تصدير مخصص لوكيل كتابة الـRequirements، يستبعد أسئلة الدراسة المفتوحة ويظهر العناصر التي تمنع الاعتماد النهائي إن وجدت.</p>
                    </div>

                    <div class="home-actions">
                        <a class="btn btn-questionnaire-secondary" href="{{ route('home') }}">
                            <i class="fa-solid fa-house"></i>
                            <span>العودة للرئيسية</span>
                        </a>
                        <a class="btn btn-questionnaire-primary" href="{{ route('final-requirements-input.download') }}">
                            <i class="fa-solid fa-download"></i>
                            <span>تحميل ملف المتطلبات MD</span>
                        </a>
                    </div>
                </div>
            </header>

            <section class="overall-progress-card technical-report-summary">
                <div class="overall-progress-head">
                    <div>
                        <div class="overall-progress-title">حالة الجاهزية للـRequirements</div>
                        <div class="overall-progress-meta">
                            {{ $reportData['stats']['included_questions'] }} سؤال نهائي مضمن
                            @if ($reportData['stats']['blocking_items'] > 0)
                                — {{ $reportData['stats']['blocking_items'] }} عنصر مانع
                            @endif
                        </div>
                    </div>
                    <div class="overall-progress-percentage">
                        {{ $reportData['is_ready'] ? 'جاهز' : 'غير جاهز' }}
                    </div>
                </div>
            </section>

            <section class="home-header technical-report-preview-card technical-report-sheet">
                <div class="technical-report-preview markdown-content">
                    {!! $renderedMarkdown !!}
                </div>

                <pre class="d-none">{{ $markdown }}</pre>
            </section>
        </div>
    </div>
@endsection
