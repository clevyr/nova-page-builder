<?php

namespace Clevyr\NovaPageBuilder;

use Inertia\Inertia;
use Clevyr\NovaPageBuilder\Models\Page;

class NovaPageBuilder
{
    public static function catchAll() {
        $page = config('nova-page-builder.model', Page::class)::where('slug', request()->path())
            ->where('is_published', 1)
            ->first();

        if ($page) {
            $metaData = [
                'meta_title' => $page->meta_title,
                'meta_keywords' => $page->meta_keywords,
                'meta_description' => $page->meta_description,
                'og_image' => $page->og_image,
            ];

            Inertia::share('metadata', $metaData);

            return Inertia::render($page->template.'/Index', [
                'page' => $page,
                'content' => json_decode($page->content),
            ]);
        } else {
            abort(404);
        }
    }
}
