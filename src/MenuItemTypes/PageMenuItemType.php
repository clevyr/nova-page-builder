<?php

namespace Clevyr\NovaPageBuilder\MenuItemTypes;

use Clevyr\NovaPageBuilder\Models\Page;

abstract class PageMenuItemType extends \Outl1ne\MenuBuilder\MenuItemTypes\BaseMenuItemType
{

    public static function getIdentifier(): string {
        return 'page';
    }

    public static function getName(): string {
        return 'Page Link';
    }

    public static function getOptions($locale): array {
        return config('nova-page-builder.model',Page::class)::all()->pluck('title', 'id')->toArray();
    }

    public static function getDisplayValue($value, ?array $data, $locale) {
        return config('nova-page-builder.model',Page::class)::find($value)->title;
    }

    public static function getValue($value, ?array $data, $locale)
    {
        $slug = config('nova-page-builder.model',Page::class)::find($value)->slug;

        if (!empty($slug) && substr($slug, 0, 1) === '/') {
            return $slug;
        }

        return '/'.$slug;
    }

    public static function getFields(): array
    {
        return [];
    }

    public static function getRules(): array
    {
        return [];
    }


    public static function getData($data = null)
    {
        return $data;
    }

    public static function getType() : string {
        return 'select';
    }
}
