<?php

namespace Dizatech\Attachment\Http\Controllers;

use App\Http\Controllers\Controller;
use Dizatech\Attachment\Http\Requests\AttachmentRemoveRequest;
use Dizatech\Attachment\Http\Requests\AttachmentUploadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Plank\Mediable\Facades\ImageManipulator;
use Plank\Mediable\Facades\MediaUploader;
use Plank\Mediable\Media;

class AjaxUploadController extends Controller
{
    public function upload( AttachmentUploadRequest  $request )
    {
        if( $request->has('disk') && in_array(config('filesystems.disks.' . $request->disk . '.driver'), ['ftp', 's3', 'sftp']) ) {
            return $this->remote($request);
        }

        return $this->local($request);

    }

    public function local($request)
    {
        if( $request->has('file_type') && ($request->file_type == 'image' || $request->file_type == 'video') ) {
            $disk = 'public';
        } else {
            ( $request->has('disk') && $request->file_type == 'attachment' )
                ? $disk = $request->disk
                : $disk = 'private';
        }

        (config('dizatech_attachment.hash_file_names'))
            ? $media = MediaUploader::fromSource($request->file)
            ->toDestination($disk, jdate()->format('Y/m'))
            ->useHashForFilename()
            ->upload()
            : $media = MediaUploader::fromSource($request->file)
            ->toDestination($disk, jdate()->format('Y/m'))
            ->upload();

        $response = new \stdClass();
        $response->status = 200;
        $response->file_key = $media->getKey();
        $response->file_name = $media->basename;

        if( $request->file_type == 'image' ) {
            $response->file_url = Storage::disk($disk)->url($media->getDiskPath());
            $variantMedia = [];
            foreach(config('dizatech_attachment.image_variant_list') as $variant) {
                $variantMedia[] = ImageManipulator::createImageVariant($media, $variant);
            }
            $response->thumbnail = Storage::disk($disk)->url($variantMedia[0]->getDiskPath());
        } elseif( $request->file_type == 'video' ) {
            $response->file_url = Storage::disk($disk)->url($media->getDiskPath());
        } elseif( $request->file_type == 'attachment' ) {
            $response->file_url =  ( config('filesystems.disks.' . $disk . '.visibility') == 'private' )
                ?
                URL::temporarySignedRoute(
                    'download.attachment',
                    now()->addHours(config('dizatech_attachment.attachment_download_link_expire_time')),
                    ['path' => $media->getDiskPath()]
                )
                :
                Storage::disk($disk)->url($media->getDiskPath())
            ;
        }
        return response()->json( $response );
    }

    public function remote($request)
    {
        (config('dizatech_attachment.hash_file_names'))
            ? $media = MediaUploader::fromSource($request->file)
            ->toDestination($request->disk, jdate()->format('Y/m'))
            ->useHashForFilename()
            ->upload()
            : $media = MediaUploader::fromSource($request->file)
            ->toDestination($request->disk, jdate()->format('Y/m'))
            ->upload();

        $response = new \stdClass();
        $response->status = 200;
        $response->file_key = $media->getKey();
        $response->file_name = $media->basename;

        if( $request->file_type == 'image' ) {
            $response->file_url = config('filesystems.disks.' . $request->disk . '.protocol')  . '://' . config('filesystems.disks.' . $request->disk . '.host') . '/' . $media->getDiskPath();
            $variantMedia = [];
            foreach(config('dizatech_attachment.image_variant_list') as $variant) {
                $variantMedia[] = ImageManipulator::createImageVariant($media, $variant);
            }
            $response->thumbnail = config('filesystems.disks.' . $request->disk . '.protocol')  . '://' . config('filesystems.disks.' . $request->disk . '.host') . '/' . $variantMedia[0]->getDiskPath();
        } else {
            $response->file_url = config('filesystems.disks.' . $request->disk . '.protocol')  . '://' . config('filesystems.disks.' . $request->disk . '.host') . '/' .  $media->getDiskPath();
        }

        return response()->json( $response );
    }

    public function remove( AttachmentRemoveRequest $request ) {
        $media = Media::query()->find($request->object_id);

        if($request->object_type == 'image') {
            foreach(config('dizatech_attachment.image_variant_list') as $variant) {
                if($media->hasVariant($variant)) {
                    Storage::disk($media->disk)->delete($media->findVariant($variant)->getDiskPath());
                    $media->findVariant($variant)->delete();
                }
            }
            Storage::disk($media->disk)->delete($media->getDiskPath());
            $media->delete();
        } else {
            Storage::disk($media->disk)->delete($media->getDiskPath());
            $media->delete();
        }

        $response = ['message' => config('dizatech_attachment.remove_file_success_message'), 'status' => 200];
        return response()->json($response);
    }
}
