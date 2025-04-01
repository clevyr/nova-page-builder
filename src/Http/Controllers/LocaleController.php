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
        $locale_exists = (array_key_exists($locale, config('nova-page-builder.locales'))
            || in_array($locale, config('nova-page-builder.locales')));

        return $request->hasSession() && $locale_exists;
    }

    public function getRedirect(Request $request, string $path = '/')
    {
        $redirect = $request->headers->get('referer') ?? $path;

        if (Str::isUrl($redirect)) {
            return $redirect;
        }

        return URL::to($redirect);
    }
}