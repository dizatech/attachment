# Laravel Attachment Package
[![GitHub issues](https://img.shields.io/github/issues/dizatech/attachment?style=flat-square)](https://github.com/dizatech/attachment/issues)
[![GitHub stars](https://img.shields.io/github/stars/dizatech/attachment?style=flat-square)](https://github.com/dizatech/attachment/stargazers)
[![GitHub forks](https://img.shields.io/github/forks/dizatech/attachment?style=flat-square)](https://github.com/dizatech/attachment/network)
[![GitHub license](https://img.shields.io/github/license/dizatech/attachment?style=flat-square)](https://github.com/dizatech/attachment/blob/master/LICENSE)


A laravel package for manage you're uploads (images and files), that use ajax and bootstrap for client side and use [plank/laravel-mediable](https://github.com/plank/laravel-mediable) for server side and store files.
<p align="center"><img src="https://s17.picofile.com/file/8421189018/dizatech_attachment.gif?raw=true"></p>

## How to install and config [dizatech/attachment](https://github.com/dizatech/attachment) package?

#### Installation

```
PHP Package:
composer require dizatech/attachment

NPM Package:
npm i dizatech-attachment
```

#### Publish Config file

```

php artisan vendor:publish --provider="Plank\Mediable\MediableServiceProvider"
php artisan vendor:publish --tag=dizatech_attachment

```

#### Create a private disk in config/filesystems.php

```

'disks' => [
        'private' => [
        'driver' => 'local',
        'root' => storage_path('app/private'),
        'visibility' => 'private'
        ],
],

```

#### Add private disk to config/mediable.php

```

/*
* Filesystems that can be used for media storage
*
* Uploader will throw an exception if a disk not in this list is selected
*/
'allowed_disks' => [
    'public',
    'private',
],

```

#### Create a symbolic link from public disk

```

php artisan storage:link

```

#### Migrate tables, to add media and mediable tables to database

```

php artisan migrate

```

#### Check you're blade file that exits csrf-token meta tag

```

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

```

#### Check you're php extensions that installed gd library driver

If you use from xampp or wamp, it's installed by default but on linux maybe not installed.
If you want to use imagick extension, read the document [here](https://laravel-mediable.readthedocs.io/en/latest/variants.html)

#### List of Properties and Use components in blade pages (e.g)
- type="image | video | attachment"
- multiple="false | true" 
- page="create | edit"
- name="string"
- label="string"
- validation="['mimes:png,jpg', 'dimensions:ratio=1/1,min_width=600,min_height=600']"
- disabled="disabled"
- required="required"  
- tooltip-title="tooltip title" and tooltip-placement="bottom | top | left | right"
- disk="public | private | ftp | ..."               
```

If we want to upload a image in create page:
<x-attachment type="image" multiple="false" page="create" name="feature" label="تصویر شاخص"></x-attachment>

And with custom validation that you can set it on all of types (image, video and attachment)
<x-attachment type="image" 
              multiple="false" 
              page="create" name="feature" label="تصویر شاخص"
              validation="['required', 'mimes:png,jpg', 'dimensions:ratio=1/1,width=600,height=600']"
></x-attachment>

And if you want to disable a input, use disabled="disabled"
<x-attachment type="image" 
              multiple="false" 
              page="create" name="feature" label="تصویر شاخص"
              disabled="disabled"
></x-attachment>

If you want to add a * before label use required="required" property
<x-attachment type="image" 
              multiple="false" 
              page="create" name="feature" label="تصویر شاخص"
              disabled="disabled"
              required="required"  
></x-attachment>

If you want to add a tooltip after label use tooltip-title="Tooltip title" 
and for change placement use from tooltip-placement="bottom" property
<x-attachment type="image" 
              multiple="false" 
              page="create" name="feature" label="تصویر شاخص"
              disabled="disabled"
              required="required"
              tooltip-title="Tooltip title"
              tooltip-placement="bottom"
></x-attachment>

If we want to upload many images in create page for gallery:
<x-attachment type="image" multiple="true" page="create" name="galleries" label="تصاویر گالری"></x-attachment>

If we want to show uploaded image in edit page and remove for change image:
<x-attachment type="image" multiple="false" page="edit" name="feature" label="تصویر شاخص" data="{{ $post->getMedia('feature')->pluck('id') }}"></x-attachment>

If we want to show uploaded images in edit page and remove for change images:
<x-attachment type="image" multiple="true" page="edit" name="galleries" label="تصاویر گالری" data="{{ $post->getMedia('galleries')->pluck('id') }}"></x-attachment>
```
```
If we want to upload a video in create page:
<x-attachment type="video" multiple="false" page="create" name="video" label="ویدیو"></x-attachment>

If we want to upload many videos in create page:
<x-attachment type="video" multiple="true" page="create" name="videos" label="ویدیو‌ها"></x-attachment>

If we want to show uploaded video in edit page and remove for change video:
<x-attachment type="video" multiple="false" page="edit" name="video" label="ویدیو" data="{{ $post->getMedia('video')->pluck('id') }}"></x-attachment>

If we want to show uploaded videos in edit page and remove for change videos:
<x-attachment type="video" multiple="true" page="edit" name="videos" label="ویدیو‌ها" data="{{ $post->getMedia('videos')->pluck('id') }}"></x-attachment>
```
```
If we want to upload a attachment file in create page:
<x-attachment type="attachment" multiple="false" page="create" name="attachment" label="فایل ضمیمه"></x-attachment>

If we want to upload many attachment files in create page for attachment:
<x-attachment type="attachment" multiple="true" page="create" name="attachments" label="فایل‌های ضمیمه"></x-attachment>

If we want to show uploaded attachment file in edit page and remove for change attachment:
<x-attachment type="attachment" multiple="false" page="edit" name="attachment" label="فایل ضمیمه" data="{{ $post->getMedia('attachment')->pluck('id') }}"></x-attachment>

If we want to show uploaded attachment files in edit page and remove for change attachments:
<x-attachment type="attachment" multiple="true" page="edit" name="attachments" label="فایل‌های ضمیمه" data="{{ $post->getMedia('attachments')->pluck('id') }}"></x-attachment>

Notice: We can use any attribute name in component.

```
```
How to use a custom disk for upload, (e.g) a ftp disk or custom local disk:

- FTP DISK:

    1- create a disk in /config/filesystems.php

        'disk_name' => [
            'driver' => 'ftp',
            'host' => env('FTP_HOST'),
            'username' => env('FTP_USERNAME'),
            'password' => env('FTP_PASSWORD'),
    
            // Optional FTP Settings...
            'port' => 21,
            'root' => '/public_html',
            'protocol' => 'http'
            // 'passive' => true,
            // 'ssl' => true,
            // 'timeout' => 30,
        ],
    
    2- add this disk name to /config/mediable.php

        'allowed_disks' => [
            'public',
            'private',
            'disk_name'
        ],

    3- use in blade
    
        <x-attachment type="image" 
                multiple="false" 
                page="create" 
                name="feature" 
                label="تصویر شاخص"
                disk="disk_name"
        ></x-attachment>

- LOCAL DISK:

    1- create a disk in /config/filesystems.php
    
        'disk_name' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

    2- add this disk name to /config/mediable.php
    
        'allowed_disks' => [
            'public',
            'private',
            'disk_name'
        ],

    3- use in blade
    
        <x-attachment type="image" 
                multiple="false" 
                page="create" 
                name="feature" 
                label="تصویر شاخص"
                disk="disk_name"
        ></x-attachment>

Notice: We can't use private disks for (image and video) types, but can use for attachmanet type.
```

#### Check you're composer.json that installed [laravel/ui](https://github.com/laravel/ui) package

```

If not installed, run this commands:
    - composer require laravel/ui
    - php artisan ui bootstrap


```

#### Set npm files, the npm package is [here](https://www.npmjs.com/package/dizatech-attachment)

```

JS file:
Add require('dizatech-attachment/js/dizatech-attachment'); to resources/js/app.js

SCSS file:
Add @import "~dizatech-attachment/scss/dizatech-attachment"; to resources/scss/app.scss

Run command:
npm run dev

```

#### Check the config file to set you're custom configs

```

<?php

return [
    'image_valid_mimes' => 'jpeg,png,jpg,gif',
    'image_maximum_size' => 5, // Megabyte
    // The first variant of list is main variant and we use it for thumbnail,
    // and if we want to define a new variant read palnk/laravel-mediable document
    // and after define variant in Service Provider. add the variant name to this array.
    'image_variant_list' => ['thumbnail'], 

    'attachment_valid_mimes' => 'pdf,doc,docx,xls,xlsx,jpeg,jpg,png,bmp',
    'attachment_maximum_size' => 10, // Megabyte
    'attachment_download_link_expire_time' => 6, // Hours

    'mimes_validation_message' => 'بارگذاری این نوع فایل مجاز نمی‌باشد.',
    'size_validation_message' => 'اندازه فایل بیشتر از حد مجاز می‌باشد.',

    'remove_file_success_message' => 'فایل با موفقیت حذف شد.',
    'remove_file_failed_message' => 'فایل مورد نظر پیدا نشد.',

    'hash_file_names' => false,

    'set_middleware_to_upload_url' => ['web'],
    'set_middleware_to_remove_url' => ['web'],
];

```

#### Use [plank/laravel-mediable](https://github.com/plank/laravel-mediable) package to attach files to Model

```

Use Mediable trait in you're model:

use Plank\Mediable\Mediable;
class Post extends Model
{
    use HasFactory, SoftDeletes, Mediable;
}

```

```

Atatch array of uploaded files to model:

public function store(Request $request, Post $post)
{
    // Save you're models data
    $post->fill($request->all());
    $post->save();

    // Attach feature image to saved post
    $feature_tag = 'feature';
    $post->syncMedia($request->feature, $feature_tag);
        
    // Attach gallery images to saved post
    $gallery_tag = 'galleries';
    $post->syncMedia($request->galleries, $gallery_tag);

    // Update Media model with feature image caption
    if($request->has('feature')) {
        foreach ($request->feature as $index => $feature) {
            Media::query()->whereId($feature)->update([
                'caption' => $request->feature_caption[$index] ?? null
            ]);
        }
    }

    // Update Media model with gallery images captions
    if($request->has('galleries')) {
        foreach ($request->galleries as $index => $gallery) {
            Media::query()->whereId($gallery)->update([
                'caption' => $request->galleries_caption[$index] ?? null
            ]);
        }
    }
}

```

#### How to get files and show in blades
```
$article->getMedia('featured_image')->count() > 0 ? $article->getMedia('featured_image')->first()->getUrl() : ''
and for show variant
$article->getMedia('featured_image')->count() > 0 ? $article->getMedia('featured_image')->first()->findVariant('thumbnail')->getUrl() : ''
```