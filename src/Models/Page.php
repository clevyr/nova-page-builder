<?php

namespace Clevyr\NovaPageBuilder\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;

class Page extends Model
{
    use HasFlexible, HasFactory;

    protected $appends = [
        'storagePath'
    ];

    /**
     * Get the raw flexible content
     *
     * @return \Whitecube\NovaFlexibleContent\Layouts\Collection
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
