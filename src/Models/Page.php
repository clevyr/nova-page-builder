<?php

namespace Clevyr\NovaPageBuilder\Models;

use Clevyr\NovaPageBuilder\Observers\PageObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;
use Whitecube\NovaFlexibleContent\Layouts\Collection;

#[ObservedBy(PageObserver::class)]
class Page extends Model
{
    use HasFlexible;

    protected $guarded = ['id'];

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
