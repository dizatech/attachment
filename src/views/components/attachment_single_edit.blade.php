<div class="row attachments">
    <div class="col-md-12">
        <div class="form-group">
            <label for=""><strong>{{ $label }}</strong></label>
            <div class="custom-file">

                <input type="file"
                       class="attachment_upload custom-file-input @error( $name ) is-invalid @enderror"
                       data-upload="{{ $upload_url }}"
                       data-remove="{{ $remove_url }}"
                       data-name="{{ $name }}"
                >
                <label class="custom-file-label" for="">بارگذاری فایل ضمیمه</label>

                @error( $name )
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

            </div>
        </div>

        @if(! is_null($validation))
            <input type="hidden"
                   class="custom_validation"
                   name="validation"
                   value="{{ $validation }}"
            >
        @endif

    </div>
    <div class="col-md-12 uploaded_files">
        @if( old($name, $data) )
            @foreach( old($name, $data) as $old_attachment )
                @php $last_attachment = \Plank\Mediable\Media::query()->find($old_attachment); @endphp
                <div class="attachment_file_upload mb-2">
                    <div class="file_info">
                        <a href="{{ \Illuminate\Support\Facades\URL::temporarySignedRoute('download.attachment', now()->addHours(6), ['path' => $last_attachment->getDiskPath()]) }}">
                            <span class="file_name">{{ $last_attachment->basename }}</span>
                        </a>
                        <input class="uploaded_file_path" type="hidden" name="{{ $name . "[]" }}" value="{{ $last_attachment->getKey() }}">
                    </div>
                    <span class="delete_file"><i class="fa fa-times"></i></span>
                </div>
            @endforeach
        @endif
    </div>
</div>
