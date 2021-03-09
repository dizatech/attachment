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
        ( $request->has('file_type') && ($request->file_type == 'image' || $request->file_type == 'video') )
            ? $disk = 'public'
            : $disk = 'private';

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
            $response->file_url = URL::temporarySignedRoute(
                'download.attachment',
                now()->addHours(config('dizatech_attachment.attachment_download_link_expire_time')),
                ['path' => $media->getDiskPath()]
            );
        }
        return response()->json( $response );
    }

    public function remove( AttachmentRemoveRequest $request ) {
        $media = Media::query()->find($request->object_id);

        if($request->object_type == 'image') {
            foreach(config('dizatech_attachment.image_variant_list') as $variant) {
                if($media->hasVariant($variant)) {
                    Storage::disk('public')->delete($media->findVariant($variant)->getDiskPath());
                    $media->findVariant($variant)->delete();
                }
            }
            Storage::disk('public')->delete($media->getDiskPath());
            $media->delete();
        } elseif($request->object_type == 'video') {
            Storage::disk('public')->delete($media->getDiskPath());
            $media->delete();
        } elseif($request->object_type == 'attachment') {
            Storage::disk('private')->delete($media->getDiskPath());
            $media->delete();
        }

        $response = ['message' => config('dizatech_attachment.remove_file_success_message'), 'status' => 200];
        return response()->json($response);
    }
}
