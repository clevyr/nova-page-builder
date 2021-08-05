<?php

namespace Clevyr\NovaPageBuilder;

use Inertia\Inertia;

class NovaPageBuilder
{
    public static function catchAll() {
        $page = config('nova-page-builder.model')::where('slug', request()->path())
            ->where('is_published', 1)
            ->first();

        if ($page) {
            return Inertia::render($page->template.'/Index', [
                'page' => $page,
                'content' => json_decode($page->content),
            ]);
        } else {
            abort(404);
        }
    }
}
