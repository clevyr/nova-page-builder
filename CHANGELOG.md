# Changelog

## Unreleased — Nova 5 / PHP 8.5 upgrade

### Breaking changes
- **Laravel Nova `^5.0` is now required.** Nova 4 is no longer supported.
- **Laravel `^12.0 | ^13.0`.** Laravel 11 support dropped — testbench 9.x still uses the deprecated `PDO::MYSQL_ATTR_SSL_CA` constant on PHP 8.5, the fix only landed on testbench 10.x, and testbench 10.x requires Laravel 12. Laravel 13's minimum (PHP 8.3) matches our floor.
- **PHP `^8.3 | ^8.4 | ^8.5`.** PHP 8.2 and below dropped.
- **`Eminiarts\Tabs\Tabs` is gone.** `src/Nova/Page.php` now uses Nova 5's native `Laravel\Nova\Tabs\Tab` (`Tab::group(...)` / `Tab::make(...)`). Any subclass of `Clevyr\NovaPageBuilder\Nova\Page` that injects `Eminiarts\Tabs\Tabs` into `fields()` will break — switch to the native API.
- **`->withToolbar()` is gone.** Native Nova 5 tabs do not render the search/refresh toolbar that the Eminiarts package put above the tabs. No replacement.
- **`emilianotisato/nova-tinymce` is removed.** The published `Default.php` page stub now uses `murdercode/nova4-tinymce-editor` (`Murdercode\TinymceEditor\TinymceEditor`). If you've published the page stubs in your app, update the import:
  ```php
  // before
  use Emilianotisato\NovaTinyMCE\NovaTinyMCE;
  // after
  use Murdercode\TinymceEditor\TinymceEditor;
  ```
  Then re-publish the TinyMCE config:
  ```bash
  php artisan vendor:publish --provider="Murdercode\TinymceEditor\FieldServiceProvider"
  ```
- **TinyMCE *field* package moved from `require` to `suggest`** — `murdercode/nova4-tinymce-editor` is now an optional install. The package's own PHP runtime never imported the field class; it was only referenced from the publishable `Default.php` page stub. Install it explicitly if you want the default stub to work:
  ```bash
  composer require murdercode/nova4-tinymce-editor:^2.0
  ```
- **TinyMCE itself is now self-hosted by this package** (added `tinymce/tinymce: ^7.0` to `require`) — no more `cdn.tiny.cloud` dependency, no API key required, no nag banner. Run `php artisan vendor:publish --tag=clevyr-nova-page-builder-tinymce` to publish the bundle into your `public/vendor/nova-page-builder/tinymce/` directory. The service provider auto-registers a `Nova::script(...)` pointing at it, so the murdercode field detects the global `window.tinymce` and skips its cloud loader.
- **`config/nova-tinymce.php` is no longer published** by this package. The new TinyMCE package has its own (`tinymce-editor.php`). Any previously-published `config/nova-tinymce.php` in your app is orphaned and can be deleted.
- **`nova-kit/nova-packages-tool` removed.** Abandoned upstream and not used.
- **The package no longer registers a global `Route::fallback()`** for the CMS catch-all. Previous versions registered the fallback from inside the package's `routes/web.php`, which silently conflicted with consumer-side fallbacks (Laravel only supports one). Register it yourself at the end of your `routes/web.php`:
  ```php
  use Clevyr\NovaPageBuilder\NovaPageBuilder;

  Route::fallback(fn () => NovaPageBuilder::catchAll());
  ```
- **`nova-page-builder.locales` config key is removed.** `nova-menu.locales` is now the single source of truth — read directly by `Page::fields()` and `LocaleController`. Previous versions duplicated `nova-menu.locales` into `nova-page-builder.locales` via a load-time `config()` call, which was fragile under non-cached config-load order on Linux. If you had customized `nova-page-builder.locales` to differ from `nova-menu.locales`, move that override into `nova-menu.locales`.
- **`LocaleController::getRedirect()` now only honors same-origin `Referer` headers.** Previously, any absolute URL or protocol-relative URL (`//evil.example/x`) in the `Referer` was accepted and used as the post-locale-switch redirect target — an open-redirect vector. Cross-origin Referers and protocol-relative URLs now fall back to the default path (`/`). If you relied on cross-origin redirects from this endpoint, redesign — that path was a security bug.

### Changed (non-breaking)
- `whitecube/nova-flexible-content` bumped to `^2.0` (Nova 5 support).
- `clevyr/nova-filemanager` bumped to `^5.0` (Nova 5 support).
- `outl1ne/nova-menu-builder` bumped to `^8.0` (Nova 5 support).
- `nova-kit/nova-devtool` (abandoned) replaced with `laravel/nova-devtool` (dev-only).
- `inertiajs/inertia-laravel` now explicitly required as `^2.0 | ^3.0`. Nova 5 transitively allows `^1.3.2 | ^2.0 | ^3.0`; we exclude the abandoned v1 line.

### Fixed
- Auto-registration of `MenuBuilder` and `FilemanagerTool` now dedups via a `Nova::serving()` callback that checks `Nova::registeredTools()` for existing instances (including subclasses) before adding its own. Previous versions called `Nova::tools(...)` unconditionally, producing duplicate sidebar entries if the consumer also registered them in their own `App\Providers\NovaServiceProvider::tools()`.

### Security
- Added `roave/security-advisories: dev-latest` to `require-dev` so `composer install` fails fast on any direct/transitive dependency with a published CVE.

---
