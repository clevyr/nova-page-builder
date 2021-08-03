<?php

use Illuminate\Support\Facades\Route;

Route::get('/page/{slug}', config('nova-page-builder.controller').'@show');
