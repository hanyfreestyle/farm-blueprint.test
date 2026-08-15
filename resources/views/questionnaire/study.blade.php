@extends('layouts.questionnaire')

@section('title', $subsection->name)

@section('content')
    @php($summary = $subsection->progress_summary)

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
                <x-questionnaire.sidebar :main-sections="$mainSections" :current-subsection-id="$subsection->id" />
            </aside>

            <div class="offcanvas offcanvas-end study-sidebar-offcanvas" tabindex="-1" id="questionnaireSidebar" aria-labelledby="questionnaireSidebarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="questionnaireSidebarLabel">الأقسام</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <x-questionnaire.sidebar :main-sections="$mainSections" :current-subsection-id="$subsection->id" />
                </div>
            </div>

            <main class="study-main">
                <header class="study-header-card">
                    <div class="study-context">{{ $subsection->parent?->name }}</div>
                    <h1 class="study-title">{{ $subsection->name }}</h1>

                    @if (filled($subsection->description))
                        <div class="study-description markdown-content">{!! \Illuminate\Support\Str::markdown($subsection->description) !!}</div>
                    @elseif (filled($subsection->parent?->description))
                        <div class="study-description markdown-content">{!! \Illuminate\Support\Str::markdown($subsection->parent->description) !!}</div>
                    @endif

                    <div class="study-progress-row">
                        <div>
                            <div class="study-progress-label">التقدم في هذا القسم</div>
                            @if ($summary['total'] > 0)
                                <div class="study-progress-meta">{{ $summary['answered'] }} / {{ $summary['total'] }} سؤال</div>
                            @else
                                <div class="study-progress-meta">لم تتم إضافة أسئلة لهذا القسم بعد.</div>
                            @endif
                        </div>
                        <div class="study-progress-percentage">{{ $summary['percentage'] ?? 0 }}%</div>
                    </div>

                    @if ($summary['total'] > 0)
                        <div class="progress questionnaire-progress" role="progressbar" aria-valuenow="{{ $summary['percentage'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar" style="width: {{ $summary['percentage'] ?? 0 }}%"></div>
                        </div>
                    @endif
                </header>

                <div class="save-indicator" data-save-indicator>كل التغييرات محفوظة</div>

                @if ($subsection->questions->isEmpty())
                    <section class="empty-subsection-card">
                        <p>لم تتم إضافة أسئلة لهذا القسم بعد.</p>
                    </section>
                @else
                    <div class="questions-list">
                        @foreach ($subsection->questions as $question)
                            <div
                                class="{{ $question->is_applicable ? '' : 'd-none' }}"
                                data-question-visibility
                                data-question-id="{{ $question->id }}"
                                data-depends-on-question-id="{{ $question->depends_on_question_id }}"
                                data-dependency-operator="{{ $question->dependency_operator?->value }}"
                                data-dependency-value="{{ $question->dependency_value }}"
                            >
                                <x-questionnaire.question-block :question="$question" :index="$loop->iteration" />
                            </div>
                        @endforeach
                    </div>
                @endif
            </main>
        </div>
    </div>
@endsection
