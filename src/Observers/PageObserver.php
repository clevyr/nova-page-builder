<?php

declare(strict_types=1);

namespace Clevyr\NovaPageBuilder\Observers;

use Clevyr\NovaPageBuilder\MenuItemTypes\PageMenuItemType;
use Clevyr\NovaPageBuilder\Models\Page;
use Illuminate\Support\Facades\DB;
use Outl1ne\MenuBuilder\Models\MenuItem;
use Throwable;

final class PageObserver
{
    /**
     * @throws Throwable
     */
    public function deleting(Page $page): void
    {
        DB::transaction(function () use ($page) {
            /** @var MenuItem|null $menu_item_model */
            $menu_item_model = config('nova-menu.menu_item_model');
            throw_unless(!empty($menu_item_model));

            $items = $menu_item_model::query()
                ->where('class', PageMenuItemType::class)
                ->where('value', $page->getKey());

            if ($items->count() > 0) {
                $items->delete();
            }
        });
    }
}