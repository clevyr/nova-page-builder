<?php

use Clevyr\Filemanager\FilemanagerField;
use Laravel\Nova\Fields\Text;
use Murdercode\TinymceEditor\TinymceEditor;

return [
    [
        'title' => 'Hero',
        'slug' => 'hero',
        'fields' => [
            Text::make('Heading', 'heading')
                ->nullable(),
            FilemanagerField::make('Background Image', 'image')
                ->displayAsImage(),
        ],
    ],
    [
        'title' => 'One Column Layout',
        'slug' => 'one-column-layout',
        'fields' => [
            TinymceEditor::make('Content', 'content'),
        ],
    ],
    [
        'title' => 'Two Column Layout',
        'slug' => 'two-column-layout',
        'fields' => [
            TinymceEditor::make('Left Column', 'left_col')
                ->nullable(),
            TinymceEditor::make('Right Column', 'right_col')
                ->nullable(),
        ],
    ],
];
