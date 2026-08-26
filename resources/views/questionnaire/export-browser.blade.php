@extends('layouts.questionnaire')

@section('title', 'تصفح ملفات الدراسة')

@push('head')
  <style>
    .export-browser-shell {
      max-width: 1500px;
    }

    .export-browser-header {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 1rem;
      margin-bottom: 1.25rem;
    }

    .export-browser-header-actions {
      display: flex;
      flex-wrap: wrap;
      gap: .65rem;
    }

    .export-stats-grid {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: .75rem;
      margin-bottom: 1rem;
    }

    .export-stat-card {
      border: 1px solid #e5e7eb;
      border-radius: 14px;
      background: #fff;
      padding: .9rem 1rem;
    }

    .export-stat-label {
      color: #64748b;
      font-size: .82rem;
      margin-bottom: .25rem;
    }

    .export-stat-value {
      color: #0f172a;
      font-size: 1.15rem;
      font-weight: 700;
    }

    .export-browser-layout {
      display: grid;
      grid-template-columns: minmax(300px, 370px) minmax(0, 1fr);
      gap: 1rem;
      align-items: start;
    }

    .export-tree-panel,
    .export-content-panel {
      border: 1px solid #e5e7eb;
      border-radius: 16px;
      background: #fff;
      overflow: hidden;
    }

    .export-tree-panel {
      position: sticky;
      top: 1rem;
    }

    .export-panel-heading {
      padding: 1rem;
      border-bottom: 1px solid #e5e7eb;
      background: #f8fafc;
    }

    .export-panel-heading h2,
    .export-panel-heading h3 {
      margin: 0;
      color: #0f172a;
      font-size: 1rem;
      font-weight: 700;
    }

    .export-tree-scroll {
      max-height: calc(100vh - 230px);
      overflow: auto;
    }

    .export-tree-section {
      border-bottom: 1px solid #edf2f7;
    }

    .export-tree-section:last-child {
      border-bottom: 0;
    }

    .export-tree-section-button {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: .75rem;
      border: 0;
      background: #fff;
      padding: .9rem 1rem;
      color: #0f172a;
      font-weight: 700;
      text-align: right;
    }

    .export-tree-section-button:hover {
      background: #f8fafc;
    }

    .export-tree-entries {
      padding: 0 .6rem .7rem;
    }

    .export-tree-entry {
      display: block;
      text-decoration: none;
      color: inherit;
      border-radius: 12px;
      padding: .7rem .75rem;
      margin-top: .25rem;
      border: 1px solid transparent;
    }

    .export-tree-entry:hover {
      background: #f8fafc;
      color: inherit;
    }

    .export-tree-entry.active {
      border-color: #bfdbfe;
      background: #eff6ff;
    }

    .export-tree-entry-main {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: .65rem;
    }

    .export-tree-entry-name {
      min-width: 0;
      color: #0f172a;
      font-size: .9rem;
      font-weight: 600;
      line-height: 1.45;
    }

    .export-tree-entry-progress {
      flex: 0 0 auto;
      border-radius: 999px;
      padding: .18rem .5rem;
      font-size: .74rem;
      font-weight: 700;
    }

    .export-tree-entry-progress.completed {
      background: #dcfce7;
      color: #166534;
    }

    .export-tree-entry-progress.in_progress {
      background: #dbeafe;
      color: #1d4ed8;
    }

    .export-tree-entry-progress.not_started,
    .export-tree-entry-progress.empty {
      background: #f1f5f9;
      color: #64748b;
    }

    .export-tree-entry-meta {
      display: flex;
      flex-wrap: wrap;
      gap: .65rem;
      margin-top: .45rem;
      color: #64748b;
      font-size: .75rem;
    }

    .export-tree-entry-meta .available {
      color: #15803d;
    }

    .export-tree-entry-meta .missing {
      color: #94a3b8;
    }

    .export-content-head {
      padding: 1.05rem 1.15rem;
      border-bottom: 1px solid #e5e7eb;
    }

    .export-breadcrumb {
      color: #64748b;
      font-size: .82rem;
      margin-bottom: .35rem;
    }

    .export-content-title-row {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 1rem;
    }

    .export-content-title {
      margin: 0;
      color: #0f172a;
      font-size: 1.25rem;
      font-weight: 700;
    }

    .export-content-path {
      color: #64748b;
      font-size: .78rem;
      direction: ltr;
      text-align: left;
      overflow-wrap: anywhere;
      margin-top: .35rem;
    }

    .export-tabs {
      display: flex;
      gap: .5rem;
      padding: .8rem 1.15rem 0;
      border-bottom: 1px solid #e5e7eb;
    }

    .export-tab {
      display: inline-flex;
      align-items: center;
      gap: .4rem;
      color: #475569;
      text-decoration: none;
      padding: .65rem .85rem;
      border-radius: 10px 10px 0 0;
      border: 1px solid transparent;
      border-bottom: 0;
      margin-bottom: -1px;
      font-weight: 600;
    }

    .export-tab.active {
      color: #0f172a;
      border-color: #e5e7eb;
      background: #fff;
    }

    .export-tab.disabled {
      color: #94a3b8;
      cursor: not-allowed;
    }

    .export-markdown {
      padding: 1.3rem 1.35rem 2rem;
      color: #1e293b;
      line-height: 1.85;
      overflow-wrap: anywhere;
    }

    .export-markdown h1,
    .export-markdown h2,
    .export-markdown h3,
    .export-markdown h4 {
      color: #0f172a;
      margin-top: 1.4em;
      margin-bottom: .65em;
      line-height: 1.45;
    }

    .export-markdown h1 {
      margin-top: 0;
      font-size: 1.55rem;
    }

    .export-markdown h2 {
      font-size: 1.25rem;
    }

    .export-markdown h3 {
      font-size: 1.05rem;
    }

    .export-markdown code {
      direction: ltr;
      unicode-bidi: isolate;
      background: #f1f5f9;
      border-radius: 6px;
      padding: .12rem .35rem;
      color: #334155;
    }

    .export-markdown pre {
      direction: ltr;
      text-align: left;
      background: #0f172a;
      color: #e2e8f0;
      border-radius: 12px;
      padding: 1rem;
      overflow: auto;
    }

    .export-markdown pre code {
      background: transparent;
      padding: 0;
      color: inherit;
    }

    .export-markdown blockquote {
      border-right: 4px solid #cbd5e1;
      background: #f8fafc;
      border-radius: 10px;
      padding: .8rem 1rem;
      color: #475569;
    }

    .export-markdown table {
      width: 100%;
      border-collapse: collapse;
      margin: 1rem 0;
    }

    .export-markdown th,
    .export-markdown td {
      border: 1px solid #e2e8f0;
      padding: .55rem .65rem;
      text-align: right;
      vertical-align: top;
    }

    .export-empty-state {
      padding: 3rem 1.25rem;
      text-align: center;
      color: #64748b;
    }

    @media (max-width: 991.98px) {
      .export-browser-header,
      .export-content-title-row {
        flex-direction: column;
      }

      .export-stats-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }

      .export-browser-layout {
        grid-template-columns: 1fr;
      }

      .export-tree-panel {
        position: static;
      }

      .export-tree-scroll {
        max-height: none;
      }
    }

    @media (max-width: 575.98px) {
      .export-stats-grid {
        grid-template-columns: 1fr;
      }

      .export-tabs {
        overflow-x: auto;
      }
    }
  </style>
@endpush

@section('content')
  <div class="questionnaire-app">
    <div class="shell shell-home export-browser-shell">
      <header class="export-browser-header">
        <div class="page-heading">
          <div class="page-kicker">Questionnaire Export</div>
          <h1>تصفح ملفات الدراسة</h1>
          <p>تصفح ملفات الإجابات المصدرة وملفات الشرح المقابلة لها من مكان واحد.</p>
        </div>

        <div class="export-browser-header-actions">
          <a class="btn btn-questionnaire-secondary" href="{{ route('home') }}">
            <i class="fa-solid fa-arrow-right"></i>
            <span>العودة للرئيسية</span>
          </a>
        </div>
      </header>

      <section class="export-stats-grid" aria-label="ملخص ملفات الدراسة">
        <div class="export-stat-card">
          <div class="export-stat-label">الأقسام الفرعية</div>
          <div class="export-stat-value">{{ $stats['subsections'] }}</div>
        </div>
        <div class="export-stat-card">
          <div class="export-stat-label">إجمالي الأسئلة</div>
          <div class="export-stat-value">{{ $stats['questions'] }}</div>
        </div>
        <div class="export-stat-card">
          <div class="export-stat-label">الأسئلة المجاب عنها</div>
          <div class="export-stat-value">{{ $stats['answered_questions'] }}</div>
        </div>
        <div class="export-stat-card">
          <div class="export-stat-label">آخر تحديث للشجرة</div>
          <div class="export-stat-value" style="font-size: .95rem;">{{ $stats['last_updated'] ?: 'غير معروف' }}</div>
        </div>
      </section>

      <div class="export-browser-layout">
        <aside class="export-tree-panel" aria-label="شجرة ملفات الدراسة">
          <div class="export-panel-heading">
            <h2><i class="fa-solid fa-folder-tree me-1"></i> شجرة الدراسة</h2>
          </div>

          <div class="export-tree-scroll accordion accordion-flush" id="exportTreeAccordion">
            @foreach ($sections as $section)
              @php
                $isCurrentMainSection = $selected && ($selected['main_number'] ?? null) === $section['number'];
                $collapseId = 'export-section-' . $section['number'];
              @endphp

              <div class="export-tree-section accordion-item">
                <h3 class="accordion-header">
                  <button
                    class="export-tree-section-button accordion-button {{ $isCurrentMainSection ? '' : 'collapsed' }}"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#{{ $collapseId }}"
                    aria-expanded="{{ $isCurrentMainSection ? 'true' : 'false' }}"
                    aria-controls="{{ $collapseId }}"
                  >
                    <span>{{ $section['number'] }}. {{ $section['name'] }}</span>
                  </button>
                </h3>

                <div
                  id="{{ $collapseId }}"
                  class="accordion-collapse collapse {{ $isCurrentMainSection ? 'show' : '' }}"
                  data-bs-parent="#exportTreeAccordion"
                >
                  <div class="export-tree-entries">
                    @foreach ($section['entries'] as $entry)
                      @php
                        $isActive = $selected && $selected['number'] === $entry['number'];
                      @endphp

                      <a
                        class="export-tree-entry {{ $isActive ? 'active' : '' }}"
                        href="{{ route('questionnaire-export.browse', ['section' => $entry['number'], 'type' => 'answers']) }}"
                      >
                        <div class="export-tree-entry-main">
                          <div class="export-tree-entry-name">{{ $entry['number'] }} — {{ $entry['name'] }}</div>
                          <span class="export-tree-entry-progress {{ $entry['progress_status'] }}">
                            {{ $entry['answered'] }}/{{ $entry['total'] }}
                          </span>
                        </div>

                        <div class="export-tree-entry-meta">
                          <span class="{{ $entry['answer_exists'] ? 'available' : 'missing' }}">
                            <i class="fa-solid {{ $entry['answer_exists'] ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                            إجابات
                          </span>
                          <span class="{{ $entry['guide_exists'] ? 'available' : 'missing' }}">
                            <i class="fa-solid {{ $entry['guide_exists'] ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                            شرح
                          </span>
                        </div>
                      </a>
                    @endforeach
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </aside>

        <main class="export-content-panel">
          @if ($selected)
            <div class="export-content-head">
              <div class="export-breadcrumb">
                {{ $selected['main_number'] }}. {{ $selected['main_name'] }}
                <span class="mx-1">/</span>
                {{ $selected['number'] }}
              </div>

              <div class="export-content-title-row">
                <div>
                  <h2 class="export-content-title">{{ $selected['name'] }}</h2>
                  <div class="export-content-path">
                    {{ $selectedType === 'guides' ? $selected['guide_path'] : $selected['answer_path'] }}
                  </div>
                </div>

                <span class="export-tree-entry-progress {{ $selected['progress_status'] }}">
                  {{ $selected['answered'] }} / {{ $selected['total'] }}
                </span>
              </div>
            </div>

            <nav class="export-tabs" aria-label="نوع الملف">
              <a
                class="export-tab {{ $selectedType === 'answers' ? 'active' : '' }}"
                href="{{ route('questionnaire-export.browse', ['section' => $selected['number'], 'type' => 'answers']) }}"
              >
                <i class="fa-solid fa-clipboard-check"></i>
                الإجابات
                @if ($selected['answer_exists'])
                  <i class="fa-solid fa-circle-check text-success"></i>
                @endif
              </a>

              @if ($selected['guide_exists'])
                <a
                  class="export-tab {{ $selectedType === 'guides' ? 'active' : '' }}"
                  href="{{ route('questionnaire-export.browse', ['section' => $selected['number'], 'type' => 'guides']) }}"
                >
                  <i class="fa-solid fa-book-open"></i>
                  الشرح
                  <i class="fa-solid fa-circle-check text-success"></i>
                </a>
              @else
                <span class="export-tab disabled" title="لم يتم إنشاء ملف الشرح بعد">
                  <i class="fa-solid fa-book-open"></i>
                  الشرح — لم يتم إنشاؤه
                </span>
              @endif
            </nav>

            @if ($selectedHtml)
              <article class="export-markdown">
                {!! $selectedHtml !!}
              </article>
            @else
              <div class="export-empty-state">
                <i class="fa-regular fa-file-lines fa-2x mb-3"></i>
                <div>الملف المطلوب غير موجود حاليًا.</div>
              </div>
            @endif
          @else
            <div class="export-empty-state">
              <i class="fa-solid fa-folder-open fa-2x mb-3"></i>
              <div>لا توجد ملفات إجابات قابلة للتصفح بعد.</div>
            </div>
          @endif
        </main>
      </div>
    </div>
  </div>
@endsection
