<?php

namespace Dizatech\Attachment;

use Dizatech\Attachment\View\Components\Attachment as AttachmentComponent;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\Image;
use Plank\Mediable\Facades\ImageManipulator;
use Plank\Mediable\ImageManipulation;
use Illuminate\Support\Facades\Validator;

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

        Validator::extend('attachment_check_disk_is_public', function ($attribute, $value, $parameters, $validator) {
            if(
                request()->has('disk')
                &&
                (request('file_type') == 'image' || request('file_type') == 'video')
                &&
                config('filesystems.disks.' . request('disk') . '.visibility') == 'private'
            ) {
                return false;
            }
            return true;
        }, config('dizatech_attachment.check_disk_is_public_message'));

        Validator::extend('attachment_disk_not_found', function ($attribute, $value, $parameters, $validator) {
            if(
                request()->has('disk')
                &&
                ! in_array(request('disk'), array_keys(config('filesystems.disks')))
            ) {
                return false;
            }
            return true;
        }, config('dizatech_attachment.disk_not_found_message'));
    }
}
