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
                    class="gallery_image_upload mb-2 custom-file-input @error( $name ) is-invalid @enderror"
                    data-upload="{{ $upload_url }}"
                    data-remove="{{ $remove_url }}"
                    data-name="{{ $name }}"
                    data-caption=""
                    multiple="multiple"
                    @if($disabled == 'disabled') disabled @endif
                >
                <label class="custom-file-label" for="">بارگذاری تصویر</label>

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
        @if( old( $name, $data) )
            @foreach( old( $name, $data) as $old_image )
                @php $last_image = \Plank\Mediable\Media::query()->find($old_image); @endphp
                <div class="gallery_file_upload mb-2">
                    <div class="file_info">
                        @if(in_array(config('filesystems.disks.' . $last_image->disk . '.driver'), ['ftp', 's3', 'sftp']))
                            @php
                                $file_url = config('filesystems.disks.' . $last_image->disk . '.protocol')  . '://' . config('filesystems.disks.' . $last_image->disk . '.host') . '/' . $last_image->getDiskPath();
                                $variantMedia = [];
                                foreach(config('dizatech_attachment.image_variant_list') as $variant) {
                                    $variantMedia[] = \Plank\Mediable\Facades\ImageManipulator::createImageVariant($last_image, $variant);
                                }
                                $thumbnail = config('filesystems.disks.' . $last_image->disk . '.protocol')  . '://' . config('filesystems.disks.' . $last_image->disk . '.host') . '/' . $variantMedia[0]->getDiskPath();
                            @endphp
                        @else
                            @php
                                $file_url = $last_image->getUrl();
                                $thumbnail = $last_image->findVariant('thumbnail')->getUrl();
                            @endphp
                        @endif
                        <a class="uploaded_file_thumbnail" data-lity target="_blank" href="{{ $file_url }}">
                            <img src="{{ $thumbnail }}" alt="img_{{ $last_image->filename }}">
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
