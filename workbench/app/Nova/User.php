<?php

namespace Workbench\App\Nova;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\Workbench\App\Models\User>
     */
    public static $model = \Workbench\App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
    ];

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Text::make('name')->sortable(),
        ];
    }
}
