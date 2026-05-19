<?php

namespace Vish4395\LaravelFileViewer;

use Illuminate\Support\Facades\Facade;

class LaravelFileViewerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-file-viewer';
    }
}
