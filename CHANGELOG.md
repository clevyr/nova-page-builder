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
- **TinyMCE is no longer in `require`.** Moved to `suggest`. The package's own PHP runtime never imported TinyMCE — it was only referenced from the publishable stub. New consumers who want the default `Default.php` stub to work out of the box must install it explicitly:
  ```bash
  composer require murdercode/nova4-tinymce-editor:^2.0
  ```
- **`config/nova-tinymce.php` is no longer published** by this package. The new TinyMCE package has its own (`tinymce-editor.php`). Any previously-published `config/nova-tinymce.php` in your app is orphaned and can be deleted.
- **`nova-kit/nova-packages-tool` removed.** Abandoned upstream and not used.
- **The package no longer auto-registers `MenuBuilder` and `FilemanagerTool`** with Nova. Previous versions called `Nova::tools([new MenuBuilder, new FilemanagerTool])` in this package's own service provider, which was inconsistent with Nova's documented convention (every other Nova tool package leaves registration to the consumer). Add them to your application's own `App\Providers\NovaServiceProvider::tools()` method:
  ```php
  use Clevyr\Filemanager\FilemanagerTool;
  use Outl1ne\MenuBuilder\MenuBuilder;

  public function tools(): array
  {
      return [
          new MenuBuilder,
          new FilemanagerTool,
      ];
  }
  ```
  If your app was *also* registering these tools itself, you were getting duplicate sidebar entries on every version of the package before this one — this change cleans that up.
- **The package no longer registers a global `Route::fallback()`** for the CMS catch-all. Previous versions registered the fallback from inside the package's `routes/web.php`, which silently conflicted with consumer-side fallbacks (Laravel only supports one). Register it yourself at the end of your `routes/web.php`:
  ```php
  use Clevyr\NovaPageBuilder\NovaPageBuilder;

  Route::fallback(fn () => NovaPageBuilder::catchAll());
  ```
- **`nova-page-builder.locales` config key is removed.** `nova-menu.locales` is now the single source of truth — read directly by `Page::fields()` and `LocaleController`. Previous versions duplicated `nova-menu.locales` into `nova-page-builder.locales` via a load-time `config()` call, which was fragile under non-cached config-load order on Linux. If you had customized `nova-page-builder.locales` to differ from `nova-menu.locales`, move that override into `nova-menu.locales`.
- **`LocaleController::getRedirect()` now only honors same-origin `Referer` headers.** Previously, any absolute URL in the `Referer` was accepted and used as the post-locale-switch redirect target — an open-redirect vector. Cross-origin Referers now fall back to the default path (`/`). If you relied on cross-origin redirects from this endpoint, redesign — that path was a security bug.

### Changed (non-breaking)
- `whitecube/nova-flexible-content` bumped to `^2.0` (Nova 5 support).
- `clevyr/nova-filemanager` bumped to `^5.0` (Nova 5 support).
- `outl1ne/nova-menu-builder` bumped to `^8.0` (Nova 5 support).
- `nova-kit/nova-devtool` (abandoned) replaced with `laravel/nova-devtool` (dev-only).
- `inertiajs/inertia-laravel` now explicitly required as `^2.0 | ^3.0`. Nova 5 transitively allows `^1.3.2 | ^2.0 | ^3.0`; we exclude the abandoned v1 line.

### Security
- Added `roave/security-advisories: dev-latest` to `require-dev` so `composer install` fails fast on any direct/transitive dependency with a published CVE.

---
