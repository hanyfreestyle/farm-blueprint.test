# Core Stack

This project is a reusable Laravel Core built for PHP 8.2, Laravel 12, Filament 4, and MySQL.

## Package Matrix

| Package | Version constraint | Purpose | Where to use it | Important notes |
| --- | --- | --- | --- | --- |
| `filament/filament` | `^4.0` | Admin panel framework | Admin resources, pages, widgets, auth UI | Main panel path is `/admin`. |
| `bezhansalleh/filament-shield` | `^4.0` | Roles, permissions, policies | Filament admin authorization | Use Shield before writing custom role logic. |
| `bezhansalleh/filament-language-switch` | `^4.0` | Admin UI language switcher | Filament interface language | Admin interface only; not for DB content. |
| `spatie/laravel-translatable` | `6.11.4` | JSON-based model translations | Translatable Eloquent attributes | Pinned for PHP 8.2 compatibility. |
| `mcamara/laravel-localization` | `2.4.1` | Localized frontend routes | Public routes and locale switching | Provides `/ar/...` and `/en/...`. |
| `swisnl/filament-backgrounds` | `2.0.0` | Filament page backgrounds | Filament auth/admin screens | Keep default reusable setup clean. |
| `ysfkaya/filament-phone-input` | `^4.0` | International phone input | Filament forms with phone/mobile fields | Prefer over custom phone components. |
| `guava/filament-icon-picker` | `^3.0` | Icon selector | Filament forms for stored icon names | Store icon identifiers, not SVG markup. |
| `intervention/image-laravel` | `^1.5.9` | Official image processing integration | Resizing, crops, thumbnails, WebP | Chosen because newer major versions require PHP 8.3. |
| `tangodev-it/filament-emoji-picker` | `2.0.0` | Optional emoji field | Selected Filament forms only | Filament 4-compatible major version. |
| `owenvoke/blade-fontawesome` | `^2.9` | Font Awesome Blade icons | Frontend Blade views and special icon needs | Pinned to `^2.9` because v3 targets PHP 8.3+. |
| `malzariey/filament-daterangepicker-filter` | `^5.0` | Advanced date range filtering | Filament tables and reports | Prefer for date/date-time analytics filters. |
| `mpdf/mpdf` | `^8.2` | PDF generation | Reports, invoices, printable documents | Use with Arabic and RTL-aware fonts/layouts. |
| `pxlrbt/filament-excel` | `^3.0` | Table exports | Standard Filament XLSX/CSV export flows | Preferred export path before custom code. |
| `fruitcake/laravel-debugbar` | `^4.4` | Development debugging | Local development only | Must not be exposed in production. |
| `laravel/boost` | `^2.5` | AI development tooling bootstrap | Local development and coding-agent workflows | Project rules in `AGENTS.md` remain authoritative. |

## Localization Layers

| Layer | Package | Responsibility |
| --- | --- | --- |
| Admin UI Translation | `bezhansalleh/filament-language-switch` | Switches the Filament interface language between Arabic and English. |
| Content Translation | `spatie/laravel-translatable` | Stores multilingual model attributes as JSON in the database. |
| Frontend Route Localization | `mcamara/laravel-localization` | Handles locale prefixes, localized URLs, and frontend locale routing. |

These layers must remain separate. Do not use one package to replace another package's responsibility.

## Core Notes

- The application locale defaults to Arabic with English fallback.
- Arabic should render as RTL through locale-aware direction logic.
- Database schema must remain migration-driven.
- Shield should stay the primary admin authorization system.
- Before adding custom features, check whether an installed Core package already solves the requirement.
