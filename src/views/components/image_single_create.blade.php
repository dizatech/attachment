<div class="row gallery">
    <div class="col-md-12">
        <div class="form-group">
            <label for="">
                @if($required == 'required')
                    <strong class="text-danger">*</strong>
                @endif
                <strong>{{ $label }}</strong>
                @if($tooltipTitle != null)
                    <span class="text-info" data-toggle="tooltip" data-placement="{{ $tooltipPlacement ?? 'top' }}" title="{{ $tooltipTitle }}">
                        <i class="fa fa-info-circle"></i>
                    </span>
                @endif
            </label>
            <div class="custom-file">

                <input
                    type="file"
                    class="gallery_image_upload mb-2 custom-file-input @error( $name) is-invalid @enderror"
                    data-upload="{{ $upload_url }}"
                    data-remove="{{ $remove_url }}"
                    data-name="{{ $name }}"
                    data-caption=""
                    @if($disabled == 'disabled') disabled @endif
                >

                <label class="custom-file-label">بارگذاری تصویر</label>

                @error( $name )
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                <span class="invalid-feedback d-none" role="alert">
                    <strong></strong>
                </span>

            </div>

        </div>

        @if(! is_null($validation))
            <input type="hidden"
                   class="custom_validation"
                   name="validation"
                   value="{{ $validation }}"
            >
        @endif
        @if(! is_null($disk))
            <input type="hidden"
                   class="custom_disk"
                   name="disk"
                   value="{{ $disk }}"
            >
        @endif

    </div>
    <div class="col-md-12 gallery_files">
        @if( old( $name ) )
            @foreach( old( $name) as $old_image )
                @php $last_image = \Plank\Mediable\Media::query()->find($old_image); @endphp
                <div class="gallery_file_upload mb-2">
                    <div class="file_info">
                        <a class="uploaded_file_thumbnail" data-lity target="_blank" href="{{ $last_image->getUrl() }}">
                            <img src="{{ $last_image->findVariant('thumbnail')->getUrl() }}" alt="img_{{ $last_image->filename }}">
                        </a>
                        <span class="file_name">{{ $last_image->basename }}</span>
                        <input class="uploaded_file_path" type="hidden" name="{{ $name . "[]" }}" value="{{ $last_image->getKey() }}">
                    </div>
                    <div class="file_caption rtl my-1">
                        <div class="row">
                            <div class="col">
                                <label>عنوان</label>
                                <input type="text" class="form-control" name="{{ $name . "_caption[]" }}" value="{{ $last_image->caption }}">
                            </div>
                        </div>
                    </div>
                    <span class="delete_file"><i class="fa fa-times"></i></span>
                </div>
            @endforeach
        @endif
    </div>
</div>
