<?php

namespace Clevyr\NovaPageBuilder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;
use Whitecube\NovaFlexibleContent\Layouts\Collection;

class Page extends Model
{
    use HasFlexible;

    protected $appends = [
        'storagePath'
    ];

    /**
     * Get the raw flexible content
     *
     * @return Collection
     */
    public function getFlexibleContentAttribute() {
        return $this->flexible('content');
    }

    /**
     * Automatically append the default storages url for files
     *
     * @return string
     */
    public function getStoragePathAttribute() {
        return Storage::url('/');
    }
}
