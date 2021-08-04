<?php

namespace Clevyr\NovaPageBuilder\Nova;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Infinety\Filemanager\FilemanagerField;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Panel;
use Whitecube\NovaFlexibleContent\Flexible;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use App\Nova\Resource;

class Page extends Resource
{
    use TabsOnEdit;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Clevyr\NovaPageBuilder\Models\Page::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title', 'content'
    ];

    function __construct($resource)
    {
        parent::__construct($resource);
        self::$model = config('nova-page-builder.model', \Clevyr\NovaPageBuilder\Models\Page::class);
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     * @throws \Exception
     */
    public function fields(Request $request)
    {
        /*
         * Details panel
         */
        $panels[] = new Panel('Details', [
                ID::make(__('ID'), 'id')
                    ->sortable()
                    ->exceptOnForms(),

                Text::make('Title', 'title')
                    ->required()
                    ->sortable(),

                Select::make('Template')
                    ->options($this->getTemplates())
                    ->default('default')
                    ->required(),
                Boolean::make('Published?', 'is_published')
                    ->default(false),

                Slug::make('Slug')
                    ->from('Title')
                    ->separator('-')
                    ->required()
            ]);

        /*
         * Content panel
         */
        // Get the available fields for this page
        $fields = $this->generateFields();

        if ($fields) {
            // Remove button if fields are locked (a page with a 'fixed' content layout)
            if ($this->fieldsLocked) {
                $fields->limit(0);
            } else {
                $fields->button('Add Section');
            }

            // Set fields
            $panels[] = new Panel('Content', [
                $fields->hideFromIndex()
                    ->collapsed()
                    ->fullWidth()
                    ->hideWhenCreating()
            ]);
        }

        /*
         * Meta Info panel
         */
        $panels[] = new Panel('Meta Information', [
            Text::make('Meta Title', 'meta_title')
                ->nullable()
                ->hideFromIndex()
                ->hideWhenCreating(),
            Text::make('Meta Keywords', 'meta_keywords')
                ->nullable()
                ->hideFromIndex()
                ->hideWhenCreating(),
            Text::make('Meta Description', 'meta_description')
                ->nullable()
                ->hideFromIndex()
                ->hideWhenCreating(),
            FilemanagerField::make('OpenGraph Image', 'og_image')
                ->nullable()
                ->hideFromIndex()
                ->displayAsImage()
                ->hideWhenCreating()
        ]);

        return [ (new Tabs($this->title . ' Page', $panels))->withToolbar() ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    /**
     * Create the fields for the page template
     *
     * @return Flexible|null
     * @throws \Exception
     */
    private function generateFields() {
        $config = false;
        $fields = null;

        // Load the config from the page's template directory
        if ($this->template) {
            $config = include(config('nova-page-builder.views_path').$this->template.'.php');
        }

        if ($config) {
            // Create flexible field layouts for each section in the config
            $fields = new Flexible('Content');
            foreach($config as $template) {
                $fields->addLayout(
                    $template['title'],
                    $template['slug'],
                    $template['fields']
                );
            }
        }

        return $fields;
    }

    /**
     * Get a list of available page templates
     *
     * @return array
     */
    private function getTemplates() {
        /*
         * Available templates are generated via resources/views/pages.
         *
         * The file name must match the Vue page file name.
         *
         * Each file needs an array of Flexible layouts.
         */
        $pages = File::allFiles(config('nova-page-builder.views_path'));
        $files = [];

        foreach ($pages as $file)
        {
            $name = explode('.', $file->getFilename())[0];
            $title = ucfirst($name);
            $files[$title] = $title;
        }

        return $files;
    }

    /**
     * Get the sidebar nav item icon
     *
     * @return string
     */
    public static function icon()
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="sidebar-icon"><path fill="var(--sidebar-icon)" class="heroicon-ui" d="M6.3 12.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H7a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM8 16h2.59l9-9L17 4.41l-9 9V16zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2h6a1 1 0 0 1 0 2H4v14h14v-6z"/></svg>';
    }
}
