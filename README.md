# Clevyr Nova Page Builder

## How To Use

### Install via Composer

```
composer require clevyr/nova-page-builder
```

### Publish Files
```
php artisan vendor:publish --tag=clevyr-nova-page-builder
```

### Run migrations
```
php artisan migrate
```

## How to Create Navigation
*To create the public facing menu:*
1. Add this line to the `boot()` method in App/Providers/AppServiceProvider.php
```
\Inertia\Inertia::share(“navigations”, nova_get_menus());
```
2. Add the `<main-nav />` Vue component to AppLayout.vue. The MainNav component comes from `resources/js/PageBuilder/partials/MainNav.vue`. This by default should be inserted next to the Dashboard link.