<?php

/**
 * Field group thay thế cho section "Kiến tạo giá trị" (khối slogan nền lớn).
 *
 * Đường đi cũ rất vòng: trang -> field section_creative_value trỏ tới một post
 * "Web builder" -> mona_render_section() đọc field `layout` của post đó -> nạp
 * partial -> partial lại get_field('section_creative_value') trên post Web
 * builder. Nội dung nằm ở post khác chứ không nằm trên trang đang sửa, nên
 * người quản trị rất khó tìm chỗ đổi nền và chữ.
 *
 * Nhóm field này nằm THẲNG trên trang, có nút bật riêng. Bật lên thì theme dùng
 * nhóm này và bỏ qua toàn bộ đường vòng cũ; tắt thì mọi thứ chạy y như trước.
 *
 * @author MONA.Media / Website
 */

defined('ABSPATH') || exit;

add_action('acf/init', function () {
    if (! function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key'    => 'group_mona_slogan_override',
        'title'  => 'Section kiến tạo giá trị (cấu hình trực tiếp)',
        'fields' => [
            [
                'key'          => 'field_mona_slogan_group',
                'label'        => 'Kiến tạo giá trị',
                'name'         => 'section_slogan',
                'type'         => 'group',
                'instructions' => 'Cấu hình nền và chữ ngay tại đây, không cần qua Web builder.',
                'layout'       => 'block',
                'sub_fields'   => [
                    [
                        'key'           => 'field_mona_slogan_show',
                        'label'         => 'Dùng cấu hình này',
                        'name'          => 'show',
                        'type'          => 'true_false',
                        'instructions'  => 'Bật để dùng nền và chữ khai báo bên dưới. '
                            . 'Tắt thì giữ nguyên cách hiển thị cũ (lấy từ Web builder).',
                        'ui'            => 1,
                        'default_value' => 0,
                    ],
                    [
                        'key'               => 'field_mona_slogan_banner_type',
                        'label'             => 'Kiểu nền',
                        'name'              => 'banner_type',
                        'type'              => 'select',
                        'choices'           => [
                            'image'   => 'Hình ảnh',
                            'video'   => 'Video tải lên',
                            'youtube' => 'YouTube',
                        ],
                        'default_value'     => 'image',
                        'return_format'     => 'value',
                        'conditional_logic' => [
                            [
                                ['field' => 'field_mona_slogan_show', 'operator' => '==', 'value' => '1'],
                            ],
                        ],
                    ],
                    [
                        'key'               => 'field_mona_slogan_image',
                        'label'             => 'Hình nền',
                        'name'              => 'images',
                        'type'              => 'image',
                        'instructions'      => 'Cũng được dùng làm ảnh chờ (poster) khi nền là video.',
                        'return_format'     => 'id',
                        'preview_size'      => 'medium',
                        'conditional_logic' => [
                            [
                                ['field' => 'field_mona_slogan_show', 'operator' => '==', 'value' => '1'],
                            ],
                        ],
                    ],
                    [
                        'key'               => 'field_mona_slogan_video',
                        'label'             => 'File video',
                        'name'              => 'video_url',
                        'type'              => 'file',
                        'return_format'     => 'url',
                        'mime_types'        => 'mp4,webm',
                        'conditional_logic' => [
                            [
                                ['field' => 'field_mona_slogan_show', 'operator' => '==', 'value' => '1'],
                                ['field' => 'field_mona_slogan_banner_type', 'operator' => '==', 'value' => 'video'],
                            ],
                        ],
                    ],
                    [
                        'key'               => 'field_mona_slogan_youtube',
                        'label'             => 'Link YouTube',
                        'name'              => 'youtube_url',
                        'type'              => 'url',
                        'conditional_logic' => [
                            [
                                ['field' => 'field_mona_slogan_show', 'operator' => '==', 'value' => '1'],
                                ['field' => 'field_mona_slogan_banner_type', 'operator' => '==', 'value' => 'youtube'],
                            ],
                        ],
                    ],
                    [
                        'key'               => 'field_mona_slogan_title',
                        'label'             => 'Chữ hiển thị',
                        'name'              => 'title',
                        'type'              => 'textarea',
                        'instructions'      => 'Xuống dòng ở đây sẽ thành xuống dòng ngoài giao diện.',
                        'rows'              => 3,
                        'new_lines'         => '',
                        'conditional_logic' => [
                            [
                                ['field' => 'field_mona_slogan_show', 'operator' => '==', 'value' => '1'],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        // 4 nơi đang dùng section này
        'location' => [
            [['param' => 'page_template', 'operator' => '==', 'value' => 'page-template/template-about.php']],
            [['param' => 'page_template', 'operator' => '==', 'value' => 'page-template/template-areas-activity.php']],
            [['param' => 'post_type', 'operator' => '==', 'value' => 'linh-vuc-hoat-dong']],
        ],
        'menu_order'            => 5,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'active'                => true,
        'description'           => 'Cấu hình nền và chữ cho khối kiến tạo giá trị, thay cho đường đi qua Web builder.',
    ]);
});
