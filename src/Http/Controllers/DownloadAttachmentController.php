<?php

namespace Dizatech\Attachment\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadAttachmentController extends Controller
{
    public function generate(Request $request)
    {
        $type = $request->type ?? 'result';

        $type_disk = [
            'result'        => 'mahamax_files1',
            'invoice'       => 'private',
            'attachment'    => 'private',
        ];
        $disk = $type_disk[$type] ?? 'mahamax_files_1';
        try {
            return Storage::disk($disk)->download($request->path);
        } catch (Exception $e) {
            return abort(404);
        }
    }
}
