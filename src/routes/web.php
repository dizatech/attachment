<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Dizatech\Attachment\Http\Controllers',
], function () {
    Route::post('dizatech_ajax_upload', 'AjaxUploadController@upload')
        ->name('dizatech_upload')->middleware(config('dizatech_attachment.set_middleware_to_upload_url'));

    Route::post('dizatech_ajax_remove', 'AjaxUploadController@remove')
        ->name('dizatech_remove')->middleware(config('dizatech_attachment.set_middleware_to_remove_url'));

    Route::get('/download/attachment', 'DownloadAttachmentController@generate')
        ->name('download.attachment')
        ->middleware('signed');
});
