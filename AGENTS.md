# Laravel Core Agent Guide

## Project Identity

This repository is a reusable Laravel Core / Starter Project.

- PHP version: 8.2
- Laravel version: 12.x
- Filament version: 4.x
- Database engine: MySQL

Do not upgrade this project from PHP 8.2, Laravel 12, or Filament 4 automatically.

Do not remove, replace, downgrade, or major-upgrade Core packages without an explicit project requirement.

Before implementing a new feature, check whether one of the Core packages already provides the required functionality.

## Core Runtime Packages

| Package | Purpose |
| --- | --- |
| `filament/filament` | Main admin panel framework for `/admin`. |
| `bezhansalleh/filament-shield` | Roles, permissions, policy generation, and Filament authorization. |
| `bezhansalleh/filament-language-switch` | Admin interface language switching for Filament. |
| `spatie/laravel-translatable` | JSON-based multilingual model attributes in the same column. |
| `mcamara/laravel-localization` | Frontend locale prefixes, route localization, and locale-aware URLs. |
| `swisnl/filament-backgrounds` | Reusable auth/admin backgrounds for Filament. |
| `ysfkaya/filament-phone-input` | International phone field for Filament forms. |
| `guava/filament-icon-picker` | Icon name picker for Filament forms. |
| `intervention/image-laravel` | Preferred image processing layer for resize/crop/thumbnail/WebP workflows. |
| `tangodev-it/filament-emoji-picker` | Optional emoji field for selected Filament forms. |
| `owenvoke/blade-fontawesome` | Font Awesome Blade components for frontend or special icon needs. |
| `malzariey/filament-daterangepicker-filter` | Advanced Filament table date and date-time range filters. |
| `mpdf/mpdf` | PDF generation with Arabic and RTL-ready support. |
| `pxlrbt/filament-excel` | Preferred export layer for Filament tables to XLSX/CSV. |

## Core Development Packages

| Package | Purpose |
| --- | --- |
| `fruitcake/laravel-debugbar` | Local-only debugging toolbar. Never expose in production. |
| `laravel/boost` | AI-oriented project guidance and agent tooling bootstrap. |

## Architecture Rules

### Authentication

- Filament admin panel path is environment-driven through `ADMIN_PANEL_PATH`.
- Do not hardcode the admin URL in routes, tests, redirects, links, or documentation.
- `App\Models\User` implements `Filament\Models\Contracts\FilamentUser`.
- Panel access is controlled through `canAccessPanel()` and Shield roles/permissions.

### Authorization

- Use Shield and Spatie permissions as the default authorization layer.
- Prefer Shield-generated permissions and policies over custom role checks.
- Super admin uses the `super_admin` role with explicit permissions, not a bypass architecture.
- Keep reusable authorization logic centralized through policies, permissions, and Filament resources.

### Multilingual Architecture

There are three separate localization layers and they must stay separate:

1. Admin UI translation: `bezhansalleh/filament-language-switch`
2. Content translation: `spatie/laravel-translatable`
3. Frontend route localization: `mcamara/laravel-localization`

Rules:

- Use `spatie/laravel-translatable` for multilingual database content.
- Use JSON columns for translatable fields where appropriate.
- Use `mcamara/laravel-localization` for `/ar/...` and `/en/...` frontend URLs.
- Keep Arabic RTL and English LTR locale-aware through layout direction logic instead of hardcoded per-page overrides.
- Admin content locales must be defined per project in `config/core.php` and reused through a shared helper such as `getProjectActiveLocales()` instead of scattering locale arrays or relying on unrelated config keys.

### Image Processing

- Use `intervention/image-laravel` for custom image manipulation.
- Do not add custom image-processing abstractions unless a real reusable requirement appears.
- If Filament's built-in upload handling is enough, do not process images unnecessarily.

### PDF and Export

- Use `mpdf/mpdf` for printable PDFs, especially when Arabic or RTL support matters.
- Use `pxlrbt/filament-excel` for normal Filament table exports before building custom exporters.

## Coding Conventions

- Follow Laravel 12 conventions.
- Follow Filament 4 conventions.
- Prefer typed properties and explicit return types where reasonable.
- Keep classes small and focused.
- Prefer Form Requests and service classes only when they solve a real reusable need.
- Do not add repositories, DTO layers, or container-heavy abstractions for appearance alone.
- Use Laravel validation, authorization, migrations, and native conventions before custom patterns.
- In this Starter Project, keep the `users` table consolidated in the base users migration when reshaping default user fields, instead of stacking extra starter-only migrations for the same schema.

## Package Selection Rules

- Before building custom admin features, check Filament and installed plugins first.
- Before building custom roles or permissions, use Shield first.
- Before building custom localized content storage, use Spatie Translatable first.
- Before building custom localized URLs, use Mcamara Localization first.
- Before building custom exports, use Filament Excel first.
- Before building custom phone or icon fields, use the installed Filament field packages first.
- When creating a new Filament resource, always create or update its Arabic and English translation files in the same change.
- Do not leave resource labels, field labels, section titles, navigation labels, or table labels hardcoded when they belong in translation files.
- For reusable upload helpers and image fields in this Core, prefer the project helper stack and `Url_Slug()` over Laravel's default slug helper when Arabic-safe slugs matter.
- For admin and content image uploads in this Core, prefer the `root_folder` filesystem disk unless a feature explicitly requires another disk.
- Any new or migrated Filament resource must be reviewed end-to-end for Filament 4 compatibility across resource class, pages, actions, schema components, table actions, and related helpers before considering the work complete.
- When a Filament resource or helper builds multilingual tabs, repeaters, SEO fields, or translated inputs, derive the locales from the shared project locale helper backed by `config/core.php`.

## Agent Documentation Policy

- Any Core configuration or policy change must be recorded in `AGENTS.md` within the matching section as part of the same change.
- Changes to admin navigation structure, localization rules, authorization policy, environment-driven paths, or web-server behavior are Core policy changes and must not be left undocumented.
- User author-profile visibility and similar admin feature toggles that live in `config/core.php` are Core configuration and must be documented here when added or changed.

## Security Rules

- Hash passwords with Laravel hashing.
- Never commit `.env`.
- Never store secrets in source code.
- Keep Debugbar local-only.
- Use Shield permissions instead of scattered manual role checks.
- Escape frontend output unless HTML is intentionally trusted and sanitized.
- Keep dependencies on supported stable versions.
- Do not disable Composer security checks.

## Web Server Policy

- Apache rewrite rules are part of the Core security and routing policy.
- Direct browser access to `/public` and `/public/...` must be prevented or redirected to the site root equivalent path.
- Prefer an Apache-level redirect for `/public` exposure when possible, because a real `public/` directory can bypass or complicate rewrite-only handling.
- Root requests should be internally rewritten to `public/` so the application can be served safely without exposing `public` in the URL.
- Do not create real public directories or files whose paths collide with application routes such as `/admin`.
- If Filament or frontend assets need custom folders, place them under non-conflicting paths like `public/images/...` instead of `public/admin/...`.
- Any change to `.htaccess` or `public/.htaccess` must preserve Laravel front-controller routing, Filament routes, authorization headers, and static asset delivery.

## Localization Delivery Rules

- Every new reusable Filament resource must ship with Arabic and English translation coverage from day one.
- Resource translations should include at minimum: navigation label, singular label, plural label, field labels, section labels, and any custom table or form text.
- Core packages that expose UI strings to administrators should be published or overridden locally when project-specific Arabic and English wording is required.
- Keep enum translations organized in a dedicated `lang/{locale}/enums/` directory when enums expose user-facing labels.
- When adding or migrating enums, update their translation files in the same change and prefer enum-driven labels in Filament forms, filters, and tables.
- For user-facing translatable author/profile fields stored on `users`, build Arabic and English tabs from the enabled system locales and keep their labels inside the resource translation files.
- Do not use `config('app.web_add_lang')` for new admin resource work in this Core. Use the shared project locale source from `config/core.php`.

## Filament Table Rules

- For table edit and delete actions, use `->iconButton()` in Filament 4 resources unless a screen has a clear exception.
- Show the `id` column in admin tables by default and make it `->sortable()`.
- The admin panel should default to full content width so resource tables and plugin tables occupy the available page space. Prefer setting the panel itself to `->maxContentWidth(Width::Full)`, and use page-level overrides only for exceptions.
- Persist table filters, search, and sort in session using:
  `->persistFiltersInSession()`
  `->persistSearchInSession()`
  `->persistSortInSession()`
- Render table filters in a modal layout with four columns in Filament 4 when the page uses table filters:
  `->filters([...], layout: Tables\Enums\FiltersLayout::Modal)`
  `->filtersFormColumns(4)`
