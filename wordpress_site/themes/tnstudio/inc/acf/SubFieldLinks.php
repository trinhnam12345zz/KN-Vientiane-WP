<?php

/**
 * Chèn thêm field vào các nhóm/repeater đang được định nghĩa trong database.
 *
 * Dùng filter acf/load_field thay vì sửa field group trong admin vì:
 *  - Định nghĩa nằm trong git, review và rollback được.
 *  - Không đụng vào dữ liệu sẵn có trong database.
 *  - Không phải thao tác kéo thả trong giao diện ACF.
 *
 * Field vẫn lưu giá trị vào postmeta như field tạo bằng tay. Điểm khác duy nhất:
 * nó không hiện trong màn hình sửa field group, chỉ hiện ở màn hình sửa nội dung.
 *
 * @author MONA.Media / Website
 */

defined('ABSPATH') || exit;

if (! function_exists('mona_acf_prepare_sub_field')) {
    /**
     * Chuẩn hoá mảng field trước khi chèn vào sub_fields của field khác.
     *
     * BẮT BUỘC phải gọi. Field lấy từ database đã đi qua acf_validate_field()
     * nên có khoá `_name` (bản sao của `name`). Mảng mình tự viết thì không.
     * ACF_Field_Group::prepare_field_for_db() ghép meta key bằng
     *     $sub_field['name'] = $field['name'] . '_' . $sub_field['_name'];
     * nên thiếu `_name` sẽ ra meta key cụt (`section_about_`, `list_eco_sys_0_`)
     * và format_value() nhét giá trị vào khoá rỗng — get_field() ngoài frontend
     * không bao giờ thấy field. Trong admin thì lưu và đọc cùng một khoá cụt nên
     * nhìn vẫn "chạy", đó là lý do lỗi này lọt qua vòng kiểm tra trước.
     *
     * acf_validate_field() không đệ quy xuống sub_fields nên phải tự đi xuống.
     */
    function mona_acf_prepare_sub_field(array $field): array
    {
        if (! empty($field['sub_fields']) && is_array($field['sub_fields'])) {
            foreach ($field['sub_fields'] as $index => $child) {
                $field['sub_fields'][$index] = mona_acf_prepare_sub_field($child);
            }
        }

        return function_exists('acf_validate_field') ? acf_validate_field($field) : $field;
    }
}

if (! function_exists('mona_acf_link_sub_field')) {
    /**
     * Định nghĩa dùng chung cho field Link được chèn thêm.
     */
    function mona_acf_link_sub_field(string $key): array
    {
        return [
            'key'           => $key,
            'label'         => 'Link',
            'name'          => 'link',
            'type'          => 'link',
            'instructions'  => 'Để trống thì thẻ này không bấm được (giữ nguyên như cũ).',
            'return_format' => 'array',
        ];
    }
}

if (! function_exists('mona_acf_use_row_layout')) {
    /**
     * Chuyển repeater sang layout "row" và ép mỗi field chiếm trọn một dòng.
     *
     * Layout mặc định là "table": mỗi field một cột. Thêm cột Link là thành 5
     * cột, ô Mô tả co lại còn vài chục pixel — đúng chỗ khách kêu khó nhìn.
     * Layout "row" đặt nhãn bên trái, ô nhập bên phải, mỗi field một dòng: đọc
     * thoáng mà không dài lê thê như layout "block".
     *
     * Phải xoá luôn wrapper width của từng sub field, vì các width đó được đặt
     * cho bố cục cột cũ; giữ lại thì hai field vẫn dính chung một dòng.
     */
    function mona_acf_use_row_layout(array $field): array
    {
        $field['layout'] = 'row';

        if (! empty($field['sub_fields']) && is_array($field['sub_fields'])) {
            foreach ($field['sub_fields'] as $index => $sub_field) {
                $field['sub_fields'][$index]['wrapper']['width'] = '';
            }
        }

        return $field;
    }
}

if (! function_exists('mona_acf_append_sub_field')) {
    /**
     * Nối thêm sub field vào cuối một repeater, bỏ qua nếu đã có field cùng name.
     */
    function mona_acf_append_sub_field(array $field, array $sub_field): array
    {
        if (empty($field['sub_fields']) || ! is_array($field['sub_fields'])) {
            return $field;
        }

        foreach ($field['sub_fields'] as $existing) {
            // Đã có người thêm field "link" trong admin thì tôn trọng cái đó
            if (isset($existing['name']) && $existing['name'] === $sub_field['name']) {
                return $field;
            }
        }

        $field['sub_fields'][] = mona_acf_prepare_sub_field($sub_field);

        return $field;
    }
}

// #14 — Hệ sinh thái công ty (trang Giới thiệu)
add_filter('acf/load_field/name=list_eco_sys', function ($field) {
    return mona_acf_use_row_layout(mona_acf_append_sub_field(
        $field,
        mona_acf_link_sub_field('field_mona_eco_sys_link')
    ));
});

// #15 — Dịch vụ & tiện ích (chi tiết Lĩnh vực hoạt động)
add_filter('acf/load_field/name=list_services_utils', function ($field) {
    return mona_acf_use_row_layout(mona_acf_append_sub_field(
        $field,
        mona_acf_link_sub_field('field_mona_services_utils_link')
    ));
});

// #16 — Logo đối tác (trang Giới thiệu)
//
// Field cũ `list_logo` là Gallery: mỗi phần tử chỉ là ID ảnh, không đính link
// được. Đổi thẳng kiểu field sẽ XOÁ SẠCH logo khách đã nhập, nên thay vì đổi,
// ở đây thêm một repeater MỚI đứng song song. Frontend ưu tiên repeater này;
// chưa nhập gì thì vẫn hiển thị Gallery cũ như trước. Nhờ vậy chuyển đổi lúc
// nào cũng được, không mất dữ liệu và không có thời điểm nào trang bị trống.
//
// Repeater được chèn NGAY SAU nút bật/tắt, tức là đứng TRÊN Gallery cũ. Đợt
// trước nó nằm cuối nhóm nên khách không thấy, mở tab Logo ra chỉ gặp Gallery
// rồi kết luận "chưa cho add link".
add_filter('acf/load_field/name=section_logo', function ($field) {
    $field = mona_acf_insert_sub_field_after($field, [
        'key'          => 'field_mona_logo_items',
        'label'        => 'Danh sách logo',
        'name'         => 'logos',
        'type'         => 'repeater',
        'instructions' => 'Mỗi dòng là một logo, có thể gắn link đích. Nhập ở đây '
            . 'thì hệ thống bỏ qua ô "Danh sách logo (bản cũ)" bên dưới.',
        'layout'       => 'block',
        'button_label' => 'Thêm logo',
        'min'          => 0,
        'sub_fields'   => [
            [
                'key'           => 'field_mona_logo_item_image',
                'label'         => 'Logo',
                'name'          => 'image',
                'type'          => 'image',
                'return_format' => 'id',
                'preview_size'  => 'thumbnail',
                'required'      => 1,
                'wrapper'       => ['width' => '40'],
            ],
            [
                'key'           => 'field_mona_logo_item_link',
                'label'         => 'Link đích',
                'name'          => 'link',
                'type'          => 'link',
                'instructions'  => 'Để trống thì logo này hiển thị bình thường nhưng không bấm được.',
                'return_format' => 'array',
                'wrapper'       => ['width' => '60'],
            ],
        ],
    ], 'show');

    // Nói rõ Gallery cũ chỉ còn là bản dự phòng, tránh nhập nhầm hai chỗ.
    foreach ($field['sub_fields'] as $index => $sub_field) {
        if (isset($sub_field['name']) && $sub_field['name'] === 'list_logo') {
            $field['sub_fields'][$index]['label']        = 'Danh sách logo (bản cũ, không gắn link được)';
            $field['sub_fields'][$index]['instructions'] = 'Chỉ dùng khi ô "Danh sách logo" phía trên còn trống.';
        }
    }

    return $field;
});

if (! function_exists('mona_acf_insert_sub_field_after')) {
    /**
     * Chèn sub field vào ngay sau một sub field khác (theo name).
     * Không tìm thấy mốc thì nối vào cuối. Bỏ qua nếu đã có field trùng name.
     */
    function mona_acf_insert_sub_field_after(array $field, array $sub_field, string $after_name): array
    {
        if (empty($field['sub_fields']) || ! is_array($field['sub_fields'])) {
            return $field;
        }

        foreach ($field['sub_fields'] as $existing) {
            if (isset($existing['name']) && $existing['name'] === $sub_field['name']) {
                return $field;
            }
        }

        $position = null;

        foreach ($field['sub_fields'] as $index => $existing) {
            if (isset($existing['name']) && $existing['name'] === $after_name) {
                $position = $index + 1;
                break;
            }
        }

        $sub_field = mona_acf_prepare_sub_field($sub_field);

        if ($position === null) {
            $field['sub_fields'][] = $sub_field;
            return $field;
        }

        array_splice($field['sub_fields'], $position, 0, [$sub_field]);

        return $field;
    }
}

// #13 — Video cho "Hình bên trái" ở section giới thiệu (trang chủ)
//
// Lọc theo KEY chứ không theo name: tên `section_about` còn được dùng ở trang
// chi tiết Lĩnh vực hoạt động, lọc theo name sẽ chèn nhầm sang cả bên đó.
// front-page.php đọc field này để đổi ảnh trái thành nút mở popup video.
add_filter('acf/load_field/key=field_6a0595b6f35aa', function ($field) {
    return mona_acf_insert_sub_field_after($field, [
        'key'           => 'field_mona_about_video_file',
        'label'         => 'Video cho hình bên trái',
        'name'          => 'video_file',
        'type'          => 'file',
        'instructions'  => 'Có video thì hình bên trái thành nút bấm mở popup phát video. '
            . 'Để trống thì vẫn là ảnh tĩnh như cũ.',
        'return_format' => 'url',
        'mime_types'    => 'mp4,webm',
    ], 'left_image');
});
