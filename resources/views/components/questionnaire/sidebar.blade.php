@props([
    'mainSections',
    'currentMainSectionId' => null,
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
                <a
                    href="{{ route('study.main-section', $mainSection) }}"
                    class="sidebar-main-link {{ $currentMainSectionId === $mainSection->id ? 'is-active' : '' }}"
                >
                    <div class="sidebar-main-head">
                        <div class="sidebar-main-name-wrap">
                            <span class="sidebar-main-name">{{ $mainSection->name }}</span>
                            @if ($summary['needs_review'])
                                <span class="sidebar-review-chip">مراجعة</span>
                            @endif
                        </div>
                        <div class="sidebar-main-meta">
                            <span>{{ $summary['answered'] }}/{{ $summary['total'] }} سؤال</span>
                            @if ($summary['percentage'] !== null)
                                <span>{{ $summary['percentage'] }}%</span>
                            @endif
                        </div>
                    </div>
                </a>

                <div class="sidebar-subsections">
                    @foreach ($mainSection->children as $subsection)
                        @php($subSummary = $subsection->progress_summary)
                        <a
                            href="{{ route('study.subsection', ['mainSection' => $mainSection, 'subsection' => $subsection]) }}"
                            class="sidebar-subsection {{ $currentSubsectionId === $subsection->id ? 'is-active' : '' }}"
                        >
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
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>
</div>
