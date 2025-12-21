<?php

namespace Feraandrei1\FilamentPagePreview;

use Illuminate\Support\ServiceProvider;

class FilamentPagePreviewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'filament-page-preview');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/filament-page-preview'),
        ], 'filament-page-preview-views');
    }

    public function register(): void
    {
        //
    }
}
