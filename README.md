# Clevyr Nova Page Builder
## How to Install
Install via Composer
```
composer require clevyr/nova-page-builder
```
Publish migrations, Default page config, PageBuilder Vue components, Nova resource, Model and PageController
```
php artisan vendor:publish --tag=clevyr-nova-page-builder
```
Migrate the database
```
php artisan migrate
```

### Routing
In the `nova-page-builder.php` config file, there is an `excluded_routes` string. This string contains url primary segments that are NOT allowed. The router ignores GET requests matching this pattern and the Nova Page CRUD validates against these as well.  

We needed to include this because the `/nova, /register, /billing...` routes were being caught by our page controller.  

Besides that, routing is taken care of automatically for Page Builder pages.

### How to Create Navigation
*To create the public facing menu:*
1. Add this line to the `boot()` method in `App/Providers/AppServiceProvider.php`
```
\Inertia\Inertia::share(“navigations”, nova_get_menus());
```
In the Vue component to AppLayout.vue (`resources/js/Layouts/AppLayout.vue`)...
1. Add `import MainNav from '@/PageBuilder/partials/MainNav';`
2. Add `MainNav` to the `components{}` object
3. Add the `<main-nav />` component to the template. This should be inserted next to the "Dashboard" link in the default Jetstream AppLayout component, you can customize and move this as needed.

---

## What’s Included:
There will be 3 new sections in Nova now: Menus, File Manager and Pages.

### Pages
Pages require templates. Templates have 2 dependencies, a config file with sections available in that template and a Vue file to render the template. The Page Config file and the Page Template parent directory need to be named the same, capitalization and all.  
ex: `resources/views/pages/About.php` & `resources/js/Pages/About/Index.vue`
##### Page Config
The config is made up of Nova fields in an array syntax. This uses the Flexible Content package. You can read more docs here: https://github.com/whitecube/nova-flexible-content. To see an example, please refer to `resources/views/pages/Default.php`
##### Page Vue Template
This package currently works off Inertia so you will create your page layouts in `resources/js/Pages/LAYOUT_NAME/Index.vue`.  To see an example, please refer to `resources/js/pages/Default/Index.vue`
###### Default Page Template Components
Out of the box, this package includes the Hero, One Column Layout and  Two Column Layout components. These are in the `resources/js/PageBuilder` directory. You can modify these at any time.
#### Page Controller
The PageController is fairly straightforward. It finds the page via the supplied slug in the URL and then returns an Inertia layout with the `page` and `content` data.

#### Menu
Menu is coming from https://github.com/optimistdigital/nova-menu-builder. The page builder package publishes the config and migrations for the menu builder package.  You can create custom menu item types and everything else from the docs.    

There is a config file named `nova-menu.php` that you can update.

##### Rendering the Menu
To render the menu in the Vue app, include the `<main-nav>` component from the `resources/js/PageBuilder/partials/MainNav.vue` file. This will render a menu with an `<jet-nav-link>` for each link. This can also be customized.

### File Manager
The File Manager is coming from https://github.com/InfinetyEs/Nova-Filemanager. 

##### Other packages included:
1. Nova TinyMCE - has a custom config file named `nova-tinymce.php`. https://github.com/emilianotisato/nova-tinymce
2. Nova Sidebar Icons - https://github.com/anaseqal/nova-sidebar-icons
3. Flexible Content - https://github.com/whitecube/nova-flexible-content
4. Nova Tabs - https://github.com/eminiarts/nova-tabs

---

## Creating Page Layouts

### Config File
To create new page layouts that will be available in the CMS, create a php file in `resources/views/pages/NAME.php`. You can view the `Default.php` file to see how it works. The basics of it is an array of Nova fields that are named and will be available in the Vue file.

### Vue File
This package is set up to use Inertia by default. To add an Inertia page, create a new Directory and Index.vue file in `resources/js/Pages`. You can see the `Default` Page as an example. The config file and Vue direcotry names need to be identical.

### Accessing Content
Page data will be passed to the views automatically, thanks to Inertia. To get specific section data, we have a Vue mixin called `SectionContent` that will return the content for a given section.
```
// About.vue
<template>
    <div v-html="getSection('intro').content"></div>
</template>

<script>
import SectionContent from '@/PageBuilder/mixins/SectionContent';

export default { 
    props: ['page', 'content'],
    mixins: [SectionContent],
}
</script>
```
This will get the content for a section with the slug “intro” from the layout’s config file.

---

## Tutorial to Create New Page Layout
We are going to create an “About” page that will just have a hero image and a wysiwyg section for an “introduction”.

### Create the layout's config file
Create the file `resources/views/pages/About.php` with the following content:
```
<?php

use Emilianotisato\NovaTinyMCE\NovaTinyMCE;
use Laravel\Nova\Fields\Text;
use Infinety\Filemanager\FilemanagerField;

return [
    // hero section
    [
        'title' => 'Hero', // title in CMS select
        'slug' => 'hero', // slug used to access content in the view
        'fields' => [ // available fields for this section
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

### Create the Vue file
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
    import SectionContent from '@/PageBuilder/mixins/SectionContent';

    export default {
        props: ['content', 'page'],
        mixins: [SectionContent],
        components: {
            AppLayout,
            Hero,
            Head,
        },
    }
</script>
```

##### Components in the above example
In the Vue file, we are importing multiple components.
1. `<app-layout>` - Vue layout from Inertia
2. `<Head>` - This is component lets you pass meta information to the layout.
3. `<Hero>` - This is a shared partial from this package

### Create Page Data
1. In Nova, navigate to Pages and click “Create Page”
2. Input a page title and select “About” from the template dropdown
3. Select “Published”
4. Click “Create Page”
5. On the View screen, click the pencil to edit the page
6. On the Edit page, click the “Content” tab
7. On the “Content” tab, click the “Add Section” button
8. Add the “Hero” section and input it’s content
9. Add the “Introduction” section and input it’s content
10. Click “Update Page”

You can view the page at `/page/{slug}` for now (this route will be updated in the future).

You can now add the page to the Main Navigation via the Nova Menu Builder.
