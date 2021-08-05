<?php

namespace Clevyr\NovaPageBuilder\MenuItemTypes;

use Clevyr\NovaPageBuilder\Models\Page;

abstract class PageMenuItemType extends \OptimistDigital\MenuBuilder\MenuItemTypes\BaseMenuItemType
{

    public static function getIdentifier(): string {
        return 'page';
    }

    public static function getName(): string {
        return 'Page Link';
    }

    public static function getOptions($locale): array {
        return Page::all()->pluck('title', 'id')->toArray();
    }

    public static function getDisplayValue($value, ?array $data, $locale) {
        return Page::find($value)->title;
    }

    public static function getValue($value, ?array $data, $locale)
    {
        return '/'.Page::find($value)->slug;
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
