<?php

return [
    'image_valid_mimes' => 'jpeg,png,jpg,gif',
    'image_maximum_size' => 5, // Megabyte
    'image_variant_list' => ['thumbnail'], // The first variant of list is main variant and we use it for thumbnail

    'video_valid_mimes' => 'mp4',
    'video_maximum_size' => 200, // Megabyte

    'attachment_valid_mimes' => 'pdf,doc,docx,xls,xlsx,jpeg,jpg,png,bmp',
    'attachment_maximum_size' => 10, // Megabyte
    'attachment_download_link_expire_time' => 6, // Hours

    'mimes_validation_message' => 'بارگذاری این نوع فایل مجاز نمی‌باشد.',
    'size_validation_message' => 'اندازه فایل بیشتر از حد مجاز می‌باشد.',

    'remove_file_success_message' => 'فایل با موفقیت حذف شد.',
    'remove_file_failed_message' => 'فایل مورد نظر پیدا نشد.',

    'check_disk_is_public_message' => 'برای نوع تصویر و ویدیو نمی‌توانید دیسک‌های خصوصی انتخاب نمایید.',
    'disk_not_found_message' => 'دیسک انتخابی شما در سامانه وجود ندارد.',

    'hash_file_names' => false,

    'set_middleware_to_upload_url' => ['web'],
    'set_middleware_to_remove_url' => ['web'],
];
