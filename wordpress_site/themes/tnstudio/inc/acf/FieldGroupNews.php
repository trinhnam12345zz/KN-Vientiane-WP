<?php

/**
 * Field group cho trang Tin tức (trang được gán làm "Trang bài viết").
 *
 * Khai báo bằng code thay vì tạo trong admin để định nghĩa field nằm trong git,
 * review được và deploy được sang môi trường khác. 18 group còn lại của site
 * hiện chỉ nằm trong database.
 *
 * @author MONA.Media / Website
 */

defined('ABSPATH') || exit;

add_action('acf/init', function () {
    if (! function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key'    => 'group_mona_news_page',
        'title'  => 'Thiết lập trang tin tức',
        'fields' => [
            [
                'key'           => 'field_mona_news_featured_posts',
                'label'         => 'Bài viết hiển thị trên slide',
                'name'          => 'featured_posts',
                'type'          => 'relationship',
                'instructions'  => 'Chọn và kéo thả để sắp xếp bài viết hiển thị trên slide đầu trang. '
                    . 'Thứ tự ở đây chính là thứ tự hiển thị. '
                    . 'Để trống thì hệ thống tự lấy 5 bài mới nhất.',
                'post_type'     => ['post'],
                'filters'       => ['search', 'taxonomy'],
                'return_format' => 'id',
                'min'           => 0,
                'max'           => 10,
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'page_type',
                    'operator' => '==',
                    'value'    => 'posts_page',
                ],
            ],
        ],
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'active'                => true,
        'description'           => 'Tùy chọn hiển thị cho trang danh sách tin tức.',
    ]);
});
