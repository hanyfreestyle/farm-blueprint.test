@props([
    'mainSections',
    'currentSubsectionId' => null,
])

<div class="questionnaire-sidebar">
    <div class="sidebar-header">
        <div class="sidebar-kicker">الأقسام</div>
        <div class="sidebar-title">خريطة الدراسة</div>
    </div>

    <div class="sidebar-tree">
        @foreach ($mainSections as $mainSection)
            @php($summary = $mainSection->progress_summary)
            <section class="sidebar-main-section">
                <div class="sidebar-main-head">
                    <div class="sidebar-main-name-wrap">
                        <span class="sidebar-main-name">{{ $mainSection->name }}</span>
                        @if ($summary['needs_review'])
                            <span class="sidebar-review-chip">مراجعة</span>
                        @endif
                    </div>
                    <div class="sidebar-main-meta">
                        @if ($summary['total'] > 0)
                            <span>{{ $summary['answered'] }}/{{ $summary['total'] }} سؤال</span>
                            <span>{{ $summary['percentage'] }}%</span>
                        @else
                            <span>0 سؤال</span>
                        @endif
                    </div>
                </div>

                <div class="sidebar-subsections">
                    @foreach ($mainSection->children as $subsection)
                        @php($subSummary = $subsection->progress_summary)
                        @php($isActive = $currentSubsectionId === $subsection->id)
                        @php($isEmpty = ! $subSummary['has_questions'])
                        @if ($isEmpty)
                            <div class="sidebar-subsection is-disabled" aria-disabled="true">
                                <div class="sidebar-subsection-main">
                                    <span class="sidebar-status-dot {{ $subSummary['status'] }}"></span>
                                    <span class="sidebar-subsection-name">{{ $subsection->name }}</span>
                                </div>
                                <div class="sidebar-subsection-meta">0 سؤال</div>
                            </div>
                        @else
                            <a href="{{ route('study.show', ['locale' => app()->getLocale(), 'section' => $subsection]) }}" class="sidebar-subsection {{ $isActive ? 'is-active' : '' }}">
                                <div class="sidebar-subsection-main">
                                    <span class="sidebar-status-dot {{ $subSummary['status'] }}"></span>
                                    <span class="sidebar-subsection-name">{{ $subsection->name }}</span>
                                    @if ($subSummary['needs_review'])
                                        <span class="sidebar-subsection-warning">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                        </span>
                                    @endif
                                </div>
                                <div class="sidebar-subsection-meta">
                                    <span>{{ $subSummary['answered'] }}/{{ $subSummary['total'] }} سؤال</span>
                                    @if ($subSummary['percentage'] !== null)
                                        <span>{{ $subSummary['percentage'] }}%</span>
                                    @endif
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>
</div>
