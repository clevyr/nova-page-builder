<?php

namespace Clevyr\NovaPageBuilder\Http\Controllers;

use Clevyr\NovaPageBuilder\Models\Page;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Routing\Controller;

class PageController extends Controller
{
    public function show(Request $request, $slug, $slug2 = null, $slug3 = null) {
        try {
            // Generate full url w/ optional slugs
            if (!is_null($slug2)) {
                $slug .= '/' . $slug2;
                if (!is_null($slug3)) {
                    $slug .= '/' . $slug3;
                }
            }

            // Find page
            $page = Page::where('slug', $slug)->where('is_published',1)->firstOrFail();

            // Return Inertia Vue with page data
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
