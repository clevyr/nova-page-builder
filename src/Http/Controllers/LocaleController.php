<?php

declare(strict_types=1);

namespace Clevyr\NovaPageBuilder\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

final class LocaleController
{
    public function __invoke(string $locale, Request $request)
    {
        if ($request->hasSession() && $this->hasLocale($request, $locale)) {
            $request->session()->put('locale', $locale);
        }

        return redirect($this->getRedirect($request));
    }

    protected function hasLocale(Request $request, string $locale): bool
    {
        $locales = config('nova-menu.locales') ?? [];
        $locale_exists = array_key_exists($locale, $locales) || in_array($locale, $locales);

        return $request->hasSession() && $locale_exists;
    }

    public function getRedirect(Request $request, string $path = '/')
    {
        $redirect = $request->headers->get('referer') ?? $path;

        if (Str::isUrl($redirect)) {
            $appHost = parse_url((string) config('app.url'), PHP_URL_HOST);
            $redirectHost = parse_url($redirect, PHP_URL_HOST);

            // Only allow same-origin redirects; otherwise fall back to the
            // default path. Prevents open-redirect via Referer header.
            if ($appHost !== null && $appHost === $redirectHost) {
                return $redirect;
            }

            return URL::to($path);
        }

        return URL::to($redirect);
    }
}