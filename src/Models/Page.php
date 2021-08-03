<?php

namespace Clevyr\NovaPageBuilder\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;

class Page extends Model
{
    use HasFlexible, HasFactory;

    protected $appends = [
        'website'
    ];

    /**
     * Get the raw flexible content
     *
     * @return \Whitecube\NovaFlexibleContent\Layouts\Collection
     */
    public function getFlexibleContentAttribute() {
        return $this->flexible('content');
    }

    public function getWebsiteAttribute() {
        return 'Website Name From Model';
    }
}
