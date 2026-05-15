<?php

use Clevyr\NovaPageBuilder\NovaPageBuilder;

// Mirror the registration consumers do in their own routes/web.php so the
// workbench (and the package's feature tests) exercise the fallback behavior
// the same way a real app would.
Route::fallback(fn () => NovaPageBuilder::catchAll());
