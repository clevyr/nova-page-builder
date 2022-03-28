<?php

namespace Clevyr\NovaPageBuilder;

use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use Clevyr\NovaPageBuilder\Models\Page;

class NovaPageBuilder
{
    public static function catchAll() {
        $locale = app()->getLocale() ?? 'en';

        // Share menu automatically if available
        if (Schema::hasTable(config('nova-menu.menus_table_name'))) {
            Inertia::share("navigations", nova_get_menus($locale));
        }

        $page = config('nova-page-builder.model', Page::class)::where('slug', request()->path())
            ->where('locale', $locale)
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
