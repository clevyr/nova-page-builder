<?php

namespace Clevyr\NovaPageBuilder\Http\Controllers;

use Clevyr\NovaPageBuilder\Models\Page;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Routing\Controller;

class PageController extends Controller
{
    public function show(Request $request, $slug) {
        try {
            $page = Page::where('slug', $slug)->where('is_published',1)->firstOrFail();

            return Inertia::render($page->template.'/Index', [
                'page' => $page,
                'content' => json_decode($page->content),
            ]);

        } catch (\Exception $ex) {
            abort(404);
        }

        return false;
    }
}
