<?php

use Laravel\Nova\Fields\Text;
use Clevyr\Filemanager\FilemanagerField;
use Emilianotisato\NovaTinyMCE\NovaTinyMCE;

return [
    [
        'title' => 'Hero',
        'slug' => 'hero',
        'fields' => [
            Text::make('Heading', 'heading')
                ->nullable(),
            FilemanagerField::make('Background Image', 'image')
                ->displayAsImage(),
        ]
    ],
    [
        'title' => 'One Column Layout',
        'slug' => 'one-column-layout',
        'fields' => [
            NovaTinyMCE::make('Content', 'content')
        ]
    ],
    [
        'title' => 'Two Column Layout',
        'slug' => 'two-column-layout',
        'fields' => [
            NovaTinyMCE::make('Left Column', 'left_col')
                ->nullable(),
            NovaTinyMCE::make('Right Column', 'right_col')
                ->nullable()
        ]
    ]
];
