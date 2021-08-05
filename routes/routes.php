<?php

use Illuminate\Support\Facades\Route;

Route::get('/{slug}/{slug2?}/{slug3?}', config('nova-page-builder.controller').'@show')->where('slug', '^(?!'.config('nova-page-builder.excluded_routes').').[a-zA-Z0-9-_\/]+$');
