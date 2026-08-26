<?php

namespace App\Http\Controllers\Questionnaire;

use App\Http\Controllers\Controller;
use Filament\Facades\Filament;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class QuestionnaireExportBrowserController extends Controller
{
    public function __invoke(): View
    {
        $this->authorizeAccess();

        $exportRoot = base_path('questionnaire-export');
        $indexPath = $exportRoot . DIRECTORY_SEPARATOR . 'INDEX.md';

        abort_unless(File::isFile($indexPath), 404, 'لم يتم إنشاء ملف questionnaire-export/INDEX.md بعد.');

        $indexMarkdown = File::get($indexPath);
        $sections = $this->parseSections($indexMarkdown, $exportRoot);
        $stats = $this->parseStats($indexMarkdown);

        $requestedSection = trim((string) request()->query('section', ''));
        $requestedType = (string) request()->query('type', 'answers');
        $requestedType = in_array($requestedType, ['answers', 'guides'], true) ? $requestedType : 'answers';

        $selected = $this->findEntry($sections, $requestedSection)
            ?? $this->firstBrowsableEntry($sections);

        $selectedType = $requestedType;

        if ($selected !== null && $selectedType === 'guides' && ! $selected['guide_exists']) {
            $selectedType = 'answers';
        }

        $selectedMarkdown = null;
        $selectedHtml = null;

        if ($selected !== null) {
            $relativePath = $selectedType === 'guides'
                ? $selected['guide_path']
                : $selected['answer_path'];

            $absolutePath = $this->absoluteExportPath($exportRoot, $relativePath);

            if ($absolutePath !== null && File::isFile($absolutePath)) {
                $selectedMarkdown = File::get($absolutePath);
                $selectedHtml = Str::markdown($selectedMarkdown, [
                    'html_input' => 'strip',
                    'allow_unsafe_links' => false,
                ]);
            }
        }

        return view('questionnaire.export-browser', [
            'sections' => $sections,
            'stats' => $stats,
            'selected' => $selected,
            'selectedType' => $selectedType,
            'selectedHtml' => $selectedHtml,
        ]);
    }

    private function authorizeAccess(): void
    {
        $adminPanel = Filament::getPanel('admin');

        abort_unless(
            auth()->check()
            && $adminPanel
            && auth()->user()?->canAccessPanel($adminPanel),
            403,
        );
    }

    /**
     * @return array<int, array{
     *     number: string,
     *     name: string,
     *     entries: array<int, array<string, mixed>>
     * }>
     */
    private function parseSections(string $markdown, string $exportRoot): array
    {
        $sections = [];
        $currentSectionIndex = null;

        foreach (preg_split('/\R/u', $markdown) ?: [] as $line) {
            if (preg_match('/^##\s+(\d+)\.\s+(.+)$/u', trim($line), $headingMatches) === 1) {
                $sections[] = [
                    'number' => $headingMatches[1],
                    'name' => trim($headingMatches[2]),
                    'entries' => [],
                ];
                $currentSectionIndex = array_key_last($sections);

                continue;
            }

            if ($currentSectionIndex === null || ! str_starts_with(trim($line), '|')) {
                continue;
            }

            $columns = array_map('trim', explode('|', trim($line, " \t\n\r\0\x0B|")));

            if (count($columns) < 7 || preg_match('/^\d+\.\d+$/', $columns[0]) !== 1) {
                continue;
            }

            $answerPath = $this->extractMarkdownPath($columns[2], 'answers');
            $guidePath = $this->extractMarkdownPath($columns[5], 'guides');
            [$answered, $total] = $this->parseProgress($columns[4]);

            $answerAbsolutePath = $this->absoluteExportPath($exportRoot, $answerPath);
            $guideAbsolutePath = $this->absoluteExportPath($exportRoot, $guidePath);

            $sections[$currentSectionIndex]['entries'][] = [
                'number' => $columns[0],
                'name' => $columns[1],
                'answer_path' => $answerPath,
                'answer_exists' => $answerAbsolutePath !== null && File::isFile($answerAbsolutePath),
                'guide_path' => $guidePath,
                'guide_exists' => $guideAbsolutePath !== null && File::isFile($guideAbsolutePath),
                'answered' => $answered,
                'total' => $total,
                'progress_status' => $this->progressStatus($answered, $total),
            ];
        }

        return array_values(array_filter(
            $sections,
            static fn (array $section): bool => $section['entries'] !== [],
        ));
    }

    /**
     * @return array<string, int|string|null>
     */
    private function parseStats(string $markdown): array
    {
        $stats = [
            'last_updated' => null,
            'main_sections' => 0,
            'subsections' => 0,
            'questions' => 0,
            'answered_questions' => 0,
            'answer_files' => 0,
            'guide_files' => 0,
        ];

        $patterns = [
            'last_updated' => '/^- وقت آخر تحديث:\s*(.+)$/mu',
            'main_sections' => '/^- الأقسام الرئيسية:\s*(\d+)$/mu',
            'subsections' => '/^- الأقسام الفرعية:\s*(\d+)$/mu',
            'questions' => '/^- إجمالي الأسئلة:\s*(\d+)$/mu',
            'answered_questions' => '/^- الأسئلة المجاب عنها:\s*(\d+)$/mu',
            'answer_files' => '/^- ملفات الإجابات المصدرة:\s*(\d+)$/mu',
            'guide_files' => '/^- ملفات الشرح الموجودة:\s*(\d+)$/mu',
        ];

        foreach ($patterns as $key => $pattern) {
            if (preg_match($pattern, $markdown, $matches) !== 1) {
                continue;
            }

            $stats[$key] = $key === 'last_updated'
                ? trim($matches[1])
                : (int) $matches[1];
        }

        return $stats;
    }

    /**
     * @param  array<int, array<string, mixed>>  $sections
     * @return array<string, mixed>|null
     */
    private function findEntry(array $sections, string $number): ?array
    {
        if ($number === '') {
            return null;
        }

        foreach ($sections as $section) {
            foreach ($section['entries'] as $entry) {
                if ($entry['number'] === $number) {
                    return $entry + [
                        'main_number' => $section['number'],
                        'main_name' => $section['name'],
                    ];
                }
            }
        }

        return null;
    }

    /**
     * @param  array<int, array<string, mixed>>  $sections
     * @return array<string, mixed>|null
     */
    private function firstBrowsableEntry(array $sections): ?array
    {
        foreach ($sections as $section) {
            foreach ($section['entries'] as $entry) {
                if ($entry['answer_exists']) {
                    return $entry + [
                        'main_number' => $section['number'],
                        'main_name' => $section['name'],
                    ];
                }
            }
        }

        return null;
    }

    private function extractMarkdownPath(string $cell, string $type): ?string
    {
        $type = preg_quote($type, '~');

        if (preg_match('~\((' . $type . '/[^)]+\.md)\)~', $cell, $matches) === 1) {
            return $matches[1];
        }

        if (preg_match('~`(' . $type . '/[^`]+\.md)`~', $cell, $matches) === 1) {
            return $matches[1];
        }

        return null;
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function parseProgress(string $progress): array
    {
        if (preg_match('/(\d+)\s*\/\s*(\d+)/', $progress, $matches) !== 1) {
            return [0, 0];
        }

        return [(int) $matches[1], (int) $matches[2]];
    }

    private function progressStatus(int $answered, int $total): string
    {
        if ($total === 0) {
            return 'empty';
        }

        if ($answered >= $total) {
            return 'completed';
        }

        if ($answered > 0) {
            return 'in_progress';
        }

        return 'not_started';
    }

    private function absoluteExportPath(string $exportRoot, ?string $relativePath): ?string
    {
        if ($relativePath === null
            || preg_match('~^(answers|guides)/[a-z0-9-]+/[a-z0-9-]+\.md$~', $relativePath) !== 1) {
            return null;
        }

        return $exportRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
    }
}
