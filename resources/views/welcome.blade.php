<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                margin: 0;
                font-family: system-ui, sans-serif;
                background:
                    radial-gradient(circle at top, #f7e6c4 0, transparent 45%),
                    linear-gradient(135deg, #fffaf0 0%, #f4efe4 100%);
                color: #2b1d0e;
            }

            .page {
                min-height: 100vh;
                display: grid;
                place-items: center;
                padding: 2rem;
            }

            .card {
                width: min(720px, 100%);
                background: rgba(255, 255, 255, 0.9);
                border: 1px solid rgba(92, 61, 31, 0.12);
                border-radius: 24px;
                padding: 2rem;
                box-shadow: 0 20px 60px rgba(92, 61, 31, 0.12);
            }

            .locale-switcher {
                display: flex;
                gap: 0.75rem;
                justify-content: flex-end;
                margin-bottom: 2rem;
            }

            .locale-switcher a {
                padding: 0.5rem 0.9rem;
                border-radius: 999px;
                border: 1px solid rgba(92, 61, 31, 0.16);
                text-decoration: none;
                color: inherit;
            }

            .locale-switcher a.active {
                background: #2b1d0e;
                color: #fffaf0;
            }

            h1 {
                font-size: clamp(2rem, 5vw, 3.5rem);
                margin: 0 0 1rem;
            }

            p {
                margin: 0 0 1rem;
                line-height: 1.7;
            }

            .actions {
                display: flex;
                gap: 1rem;
                flex-wrap: wrap;
                margin-top: 2rem;
            }

            .button {
                display: inline-block;
                padding: 0.85rem 1.2rem;
                border-radius: 999px;
                text-decoration: none;
                font-weight: 600;
            }

            .button-primary {
                background: #d97706;
                color: #fffaf0;
            }

            .button-secondary {
                border: 1px solid rgba(92, 61, 31, 0.16);
                color: inherit;
            }
        </style>
    </head>
    <body>
        <div class="page">
            <main class="card">
                <nav class="locale-switcher" aria-label="Language switcher">
                    @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <a
                            href="{{ LaravelLocalization::getLocalizedURL($localeCode, route('home', absolute: false), [], true) }}"
                            class="{{ app()->getLocale() === $localeCode ? 'active' : '' }}"
                            rel="alternate"
                            hreflang="{{ $localeCode }}"
                        >
                            {{ $properties['native'] }}
                        </a>
                    @endforeach
                </nav>

                @if (app()->getLocale() === 'ar')
                    <h1>نواة Laravel متعددة اللغات</h1>
                    <p>هذا المشروع هو نقطة انطلاق قابلة لإعادة الاستخدام مبنية على Laravel 12 وFilament 4 مع دعم كامل للواجهة الإدارية، الصلاحيات، وتعدد اللغات.</p>
                    <p>الواجهة الأمامية تستخدم مسارات محلية بصيغة <code>/ar</code> و<code>/en</code>، بينما تدعم لوحة التحكم التبديل بين لغات الواجهة بشكل مستقل.</p>
                    <div class="actions">
                        <a class="button button-primary" href="{{ url('/admin') }}">فتح لوحة الإدارة</a>
                        <a class="button button-secondary" href="{{ url('/docs/CORE_STACK.md') }}">عرض توثيق النواة</a>
                    </div>
                @else
                    <h1>Reusable Laravel Core</h1>
                    <p>This starter is a reusable Laravel 12 and Filament 4 foundation with admin auth, roles, permissions, multilingual content support, and localized frontend routes.</p>
                    <p>The frontend uses localized URLs at <code>/ar</code> and <code>/en</code>, while the Filament admin panel can switch its own interface language independently.</p>
                    <div class="actions">
                        <a class="button button-primary" href="{{ url('/admin') }}">Open Admin Panel</a>
                        <a class="button button-secondary" href="{{ url('/docs/CORE_STACK.md') }}">View Core Docs</a>
                    </div>
                @endif
            </main>
        </div>
    </body>
</html>
