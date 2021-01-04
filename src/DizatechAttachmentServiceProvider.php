<?php

namespace Dizatech\Attachment;

use Dizatech\Attachment\View\Components\Attachment as AttachmentComponent;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\Image;
use Plank\Mediable\Facades\ImageManipulator;
use Plank\Mediable\ImageManipulation;

class DizatechAttachmentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }


    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/views','dizatech_attachment');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->mergeConfigFrom(__DIR__ . '/config/dizatech_attachment.php', 'dizatech_attachment');
        $this->publishes([
            __DIR__.'/config/dizatech_attachment.php' =>config_path('dizatech_attachment.php')
        ], 'dizatech_attachment');

        $this->loadViewComponentsAs('', [
            AttachmentComponent::class
        ]);

        ImageManipulator::defineVariant(
            'thumbnail',
            ImageManipulation::make(function (Image $image) {
                $image->fit(200, 200);
            })->outputPngFormat()
        );
    }
}
