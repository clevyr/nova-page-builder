# Clevyr Nova Page Builder

## How to Install
```
composer require clevyr/nova-page-builder
php artisan vendor:publish --tag=clevyr-nova-page-builder
php artisan migrate
```

## What Included:
There will be 3 new sections in Nova now: Menus, File Manager and Pages.

#### Pages
This is the bulk of the package. You can manage Pages. Pages have "flexible content fields". You can read more docs here: https://github.com/whitecube/nova-flexible-content. Flexible Fields are what helps us create the available sections for page content.

#### Menu
Menu is coming from https://github.com/optimistdigital/nova-menu-builder. The page builder package publishes the config and migrations for menu builder package.  You can create custom menu item types and everything else from the docs.    

There is a config file named `nova-menu.php` that you can update.

#### File Manager
The File Manager is coming from https://github.com/InfinetyEs/Nova-Filemanager. 

##### Other packages included:
1. Nova TinyMCE - has a config file named `nova-tinymce.php`
2. Nova Sidebar Icons
3. Flexible Fields
4. Nova Tabs

## Creating Page Layouts

### Config File
To create new page layouts that will be available in the CMS, create a php file in `resources/views/pages/NAME.php`. You can view the `Default.php` file to see how it works. The basics of it is an array of Nova fields that are named and will be available in the Vue file.

### Vue File
This package is set up to use Inertia by default. To add an Inertia page, create a new Directory and Index.vue file in `resources/js/Pages`. You can see the `Default` Page as an example. File name capitilzation needs to match the page template config file.

### Accessing Content
Page data will be passed to the views automatically, thanks to Inertia. To get specific section data, use something like the following:
```
<template>
    <div v-html="getSection('intro').content"></div>
</template>

```
```
methods: {
    /**
        Return the content for a section denoted by the section's "slug"
    **/
    getSection(slug) {
        const section = this.content.filter((section) => {
            return section.layout === slug;
        });

        if (section) {
            return section[0]['attributes'];
        }

        return false;
    },
}
```
This will get the content for a section with the slug "intro" from the layout's config file.

## Step-By-Step to Create New Page Layout
We are going to create an "About" page that will just have a hero image and a wysiwyg section for an "introduction".
##### Create config file
Create the file `resources/views/pages/About.php` with the following content:
```
<?php

use Emilianotisato\NovaTinyMCE\NovaTinyMCE;
use Laravel\Nova\Fields\Text;
use Infinety\Filemanager\FilemanagerField;

return [
    // hero section
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
    // introduction section
    [
        'title' => 'Introduction',
        'slug' => 'intro',
        'fields' => [
            NovaTinyMCE::make('Content', 'content')
        ]
    ],
];
```
##### Create the Vue file
Create the file `resources/js/Pages/About/Index.vue` with the following content:
```
<template>
    <app-layout>
        <Head :page="page" />
        <Hero :content="getSection('hero')" />
        <div class="w-full" v-html="getSection('intro').content"></div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Hero from '@/PageBuilder/sections/Hero';
    import Head from '@/PageBuilder/partials/Head';

    export default {
        props: ['content', 'page'],
        components: {
            AppLayout,
            Hero,
            Head,
        },
        methods: {
            getSection(slug) {
                const section = this.content.filter((section) => {
                    return section.layout === slug;
                });

                if (section) {
                    return section[0]['attributes'];
                }

                return false;
            },
        },
    }
</script>
```
###### Components in example
In the Vue file, we are importing multiple components.
1. App Layout - Layout from Inertia
2. Head - This is the Inertia `<head>` file. This is how we pass meta information to the layout.
3. Hero - This is a shared partial from the page builder

#### Create Page Data
1. In Nova, navigate to Pages and click "Create Page"
2. Input a page title and select "About" from the template dropdown
3. Select "Published"
4. Click "Create Page"
5. On the View screen, click the pencil to edit the page
6. On the Edit page, click the "Content" tab
7. On the "Content" tab, click the "Add Section" button
8. Add the "Hero" section and input it's content
9. Add the "Introduction" section and input it's content
10. Click "Update Page"

You can view the page at `/page/{slug}` for now (this route will be updated in the future).

You can now add the page to the Main Navigation via the Nova Menu Builder.
