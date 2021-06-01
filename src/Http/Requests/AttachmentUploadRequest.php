<?php

namespace Dizatech\Attachment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttachmentUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Convert string of validations to array
        $preValidation = [];
        if(request()->has('validation')) {
            preg_match_all('/[\'"](.*?)[\'"]/', request('validation'), $array);
            $preValidation = $array[1];
            if( request('file_type') == 'image' || request('file_type') == 'video' ) {
                $preValidation = array_merge($preValidation, ['attachment_check_disk_is_public', 'attachment_disk_not_found']);
            }
            if( request('file_type') == 'attachment' ) {
                $preValidation = array_merge($preValidation, ['attachment_disk_not_found']);
            }
        }
        // Prepare validation
        if(!empty($preValidation)) {
            $validation = $preValidation;
        } else {
            $validation =
                (request('file_type') == 'image')
                    ? ['required', 'mimes:' . config('dizatech_attachment.image_valid_mimes'), 'max:' . (config('dizatech_attachment.image_maximum_size') * 1024), 'attachment_check_disk_is_public', 'attachment_disk_not_found']
                    : ( (request('file_type') == 'video')
                    ? ['required', 'mimes:' . config('dizatech_attachment.video_valid_mimes'), 'max:' . (config('dizatech_attachment.video_maximum_size') * 1024), 'attachment_check_disk_is_public', 'attachment_disk_not_found']
                    : ['required', 'mimes:' . config('dizatech_attachment.attachment_valid_mimes'), 'max:' . (config('dizatech_attachment.attachment_maximum_size') * 1024), 'attachment_disk_not_found'] );
        }
        return [
            'file' => $validation,
            'file_type' => ['required', 'in:image,attachment,video'],
        ];
    }

    public function messages()
    {
        return [
            'file.mimes' => config('dizatech_attachment.mimes_validation_message'),
            'file.max' => config('dizatech_attachment.size_validation_message')
        ];

    }
}
