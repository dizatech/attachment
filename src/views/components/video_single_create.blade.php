<div class="row videos">
    <div class="col-md-12">
        <div class="form-group">
            <label for=""><strong>{{ $label }}</strong></label>
            <div class="custom-file">

                <input type="file"
                       class="video_upload custom-file-input @error( $name ) is-invalid @enderror"
                       data-upload="{{ $upload_url }}"
                       data-remove="{{ $remove_url }}"
                       data-name="{{ $name }}"
                >
                <label class="custom-file-label" for="">بارگذاری ویدیو</label>

                @error( $name )
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

            </div>
        </div>
    </div>
    <div class="col-md-12 video_files">
        @if( old($name) )
            @foreach( old($name) as $old_video )
                @php $last_video = \Plank\Mediable\Media::query()->find($old_video); @endphp
                <div class="video_file_upload mb-2">
                    <div class="file_info">
                        <a href="{{ $last_video->getUrl() }}">
                            <span class="file_name">{{ $last_video->basename }}</span>
                        </a>
                        <input class="uploaded_file_path" type="hidden" name="{{ $name . "[]" }}" value="{{ $last_video->getKey() }}">
                    </div>

                    <span class="delete_file"><i class="fa fa-times"></i></span>
                </div>
            @endforeach
        @endif
    </div>
</div>
