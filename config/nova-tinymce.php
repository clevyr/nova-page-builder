<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Options
    |--------------------------------------------------------------------------
    |
    | Here you can define the options that are passed to all NovaTinyMCE
    | fields by default.
    |
    */

    'default_options' => [
        // Styles
        'content_css' => '/vendor/tinymce/skins/content/writer/content.css',
        'skin_url' => '/vendor/tinymce/skins/ui/oxide',
        'path_absolute' => '/',
        'height' => '400',

        // Toolbars
        'plugins' => 'lists preview anchor pagebreak image wordcount fullscreen directionality media image link table code',
        'toolbar' => 'styleselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist | link image media anchor',

        // Tables
        'table_responsive_width' => true,
        'table_sizing_mode' => 'responsive',
        'table_column_resizing' => 'resizetable',

        // Uploading
        'relative_urls' => false,
        'use_lfm' => true,
        'lfm_url' => 'nova/'.config('filemanager.path', 'nova-filemanager'),

        // Images
        'a11y_advanced_options' => true,
        'image_advtab' => true,
        'image_class_list' => [
            'w-100' => 'Full Width',
            'w-50' => 'Half Width',
            'object-fit' => 'Stretch to Fit',
        ],
        'image_title' => true,

        // Links
        'link_class_list' => [
            [
                'title' => 'None',
                'value' => ''
            ],
            [
                'title' => 'Button',
                'value' => 'btn p-2 bg-red-100 inline-block m-2'
            ]
        ]
    ],
];
