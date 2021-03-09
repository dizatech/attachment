<?php

namespace Dizatech\Attachment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttachmentRemoveRequest extends FormRequest
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
        return [
            'object_id' => ['required', 'exists:media,id'],
            'object_type' => ['required', 'in:image,attachment,video']
        ];
    }

    public function messages()
    {
        return [
            'object_id.exists' => config('dizatech_attachment.remove_file_failed_message'),
            'object_type.in' => config('dizatech_attachment.remove_file_failed_message')
        ];

    }
}
