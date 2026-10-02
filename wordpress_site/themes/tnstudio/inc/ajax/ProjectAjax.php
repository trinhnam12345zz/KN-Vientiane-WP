<?php
defined('ABSPATH') || exit;

if (! function_exists('mona_term_in_lang')) {
  /**
   * True if a term slug belongs to the given language, based on the import
   * convention: translated terms have slug "...-{lang}"; the default (vi) has none.
   */
  function mona_term_in_lang($slug, $lang) {
    if (empty($lang)) return true;

    // Cắt phần vùng khỏi mã ngôn ngữ trước khi so.
    //
    // Slug chỉ gắn hậu tố mã GỐC: `du-an-tieu-bieu-zh`. Trong khi WPML đăng ký
    // tiếng Trung là `zh-hant` nên wpml_current_language trả về `zh-hant` —
    // so thẳng thì tìm hậu tố `-zh-hant`, không term nào khớp, hàm trả về false
    // cho cả 3 danh mục dự án tiếng Trung. Hệ quả ngoài trang chủ: section Dự án
    // chỉ còn mỗi tiêu đề, không tab không thẻ nào (đúng ảnh khách chụp).
    $lang = strtok($lang, '-');

    $suffixes = ['en', 'lo', 'ko', 'zh'];
    if ($lang === 'vi') {
      foreach ($suffixes as $s) {
        if (substr($slug, -strlen('-' . $s)) === '-' . $s) return false;
      }
      return true;
    }
    return substr($slug, -strlen('-' . $lang)) === '-' . $lang;
  }
}

if (! function_exists('mona_ajax_get_projects')) {
  function mona_ajax_get_projects()
  {
    try {
      // if (! check_ajax_referer('mona-ajax-security', 'security', false)) {
      //   throw new Exception(__('Hành động không được xác thực', 'monamedia'));
      // }

      if (! empty($_POST['lang'])) {
        do_action('wpml_switch_language', sanitize_key($_POST['lang']));
      }

      $response = [
        'success' => true,
        'data' => [],
      ];

      $posts_per_page = filter_input(INPUT_POST, 'posts_per_page', FILTER_VALIDATE_INT);
      if (! $posts_per_page) {
        $posts_per_page = 12; // Số item mặc định
      }
      $posts_per_page = max(1, min(50, absint($posts_per_page)));

      $paged = filter_input(INPUT_POST, 'paged', FILTER_VALIDATE_INT);
      if (! $paged) {
        $paged = 1;
      }
      $paged = max(1, absint($paged));

      $cur_lang = ! empty($_POST['lang']) ? sanitize_key($_POST['lang']) : apply_filters('wpml_current_language', null);

      // Định cấu hình Query cho Post Type 'du-an'
      $args = [
        'post_type'           => 'du-an',
        'post_status'         => 'publish',
        'posts_per_page'      => $posts_per_page,
        'paged'               => $paged,
        'fields'              => 'ids',
        'ignore_sticky_posts' => true,
        'suppress_filters'    => false,
        'orderby'             => ['date' => 'DESC'],
      ];

      $query = new WP_Query($args);

      $seen_ids = [];
      if ($query->have_posts()) {
        $response['data']['items'] = [];
        while ($query->have_posts()) {
          $query->the_post();
          $post_id = get_the_ID();

          // Safety: skip posts not in the current language and dedupe repeats.
          if ($cur_lang) {
            $post_lang = function_exists('pll_get_post_language') ? pll_get_post_language($post_id, 'slug') : null;
            if ($post_lang && $post_lang !== $cur_lang) {
              continue;
            }
            $pl = apply_filters('wpml_post_language_details', null, $post_id);
            if (is_array($pl) && ! empty($pl['language_code']) && $pl['language_code'] !== $cur_lang) {
              continue;
            }
          }
          if (isset($seen_ids[$post_id])) continue;
          $seen_ids[$post_id] = true;

          // Lấy URL thumbnail thay vì HTML string
          $thumbnail_url = '';
          if (has_post_thumbnail($post_id)) {
            $thumbnail_url = wp_get_attachment_image_url(get_post_thumbnail_id($post_id), 'full');
          }

          $permalink = get_permalink($post_id);
          if ($cur_lang && $cur_lang !== 'vi' && function_exists('mona_localize_url')) {
            $permalink = mona_localize_url($permalink, $cur_lang);
          }

          $response['data']['items'][] = [
            'id'          => $post_id,
            'name'        => get_field('project_title_short') ?: get_the_title($post_id),
            'description' => wp_strip_all_tags(get_the_excerpt($post_id)),
            'thumbnail'   => $thumbnail_url,
            'permalink'   => $permalink,
            'date'        => get_the_date('d/m/Y', $post_id),
          ];
        }

        wp_reset_postdata();

        // Thêm pagination info
        $response['data']['totalItem'] = count($response['data']['items']);
        $response['data']['totalPage'] = max(1, ceil(count($response['data']['items']) / $posts_per_page));
        $response['data']['pageIndex'] = $paged;
      } else {
        $response['data']['items'] = [];
        $response['data']['totalItem'] = 0;
        $response['data']['totalPage'] = 1;
        $response['data']['pageIndex'] = 1;
      }

      wp_send_json($response);
    } catch (\Throwable $th) {
      wp_send_json([
        'success' => false,
        'errors' => [
          __('Đã xảy ra sự cố khi tải dự án', 'monamedia'),
        ],
      ]);
    }

    wp_die();
  }
}

add_action('wp_ajax_mona_ajax_get_projects', 'mona_ajax_get_projects');
add_action('wp_ajax_nopriv_mona_ajax_get_projects', 'mona_ajax_get_projects');

if (! function_exists('mona_ajax_get_project_categories')) {
  function mona_ajax_get_project_categories()
  {
    try {
      // if (! check_ajax_referer('mona-ajax-security', 'security', false)) {
      //   throw new Exception(__('Hành động không được xác thực', 'monamedia'));
      // }

      if (! empty($_POST['lang'])) {
        do_action('wpml_switch_language', sanitize_key($_POST['lang']));
      }

      $response = [
        'success' => true,
        'data' => [],
      ];

      // 1. Nhận và kiểm tra ID danh mục được truyền lên từ Tab
      $category_id = filter_input(INPUT_POST, 'categoryId', FILTER_VALIDATE_INT);
      if (! $category_id) {
        throw new Exception(__('Danh mục không hợp lệ', 'monamedia'));
      }

      $posts_per_page = filter_input(INPUT_POST, 'posts_per_page', FILTER_VALIDATE_INT);
      if (! $posts_per_page) {
        $posts_per_page = 3;
      }
      $posts_per_page = max(1, min(50, absint($posts_per_page)));

      $paged = filter_input(INPUT_POST, 'paged', FILTER_VALIDATE_INT);
      if (! $paged) {
        $paged = 1;
      }
      $paged = max(1, absint($paged));

      $cur_lang = ! empty($_POST['lang']) ? sanitize_key($_POST['lang']) : apply_filters('wpml_current_language', null);

      $args = [
        'post_type'           => 'du-an',
        'post_status'         => 'publish',
        'posts_per_page'      => $posts_per_page,
        'paged'               => $paged,
        'fields'              => 'ids',
        'ignore_sticky_posts' => true,
        'suppress_filters'    => false,
        'orderby'             => ['date' => 'DESC'],
        'tax_query'           => [
          [
            'taxonomy' => 'danh-muc-du-an',
            'field'    => 'term_id',
            'terms'    => $category_id,
          ]
        ]
      ];

      $query = new WP_Query($args);

      $seen_ids = [];
      if ($query->have_posts()) {
        $response['data']['items'] = [];
        while ($query->have_posts()) {
          $query->the_post();
          $post_id = get_the_ID();

          // Safety: skip posts not in the current language and dedupe repeats.
          if ($cur_lang) {
            $post_lang = function_exists('pll_get_post_language') ? pll_get_post_language($post_id, 'slug') : null;
            if ($post_lang && $post_lang !== $cur_lang) {
              continue;
            }
            $pl = apply_filters('wpml_post_language_details', null, $post_id);
            if (is_array($pl) && ! empty($pl['language_code']) && $pl['language_code'] !== $cur_lang) {
              continue;
            }
          }
          if (isset($seen_ids[$post_id])) continue;
          $seen_ids[$post_id] = true;

          // Lấy URL thumbnail thay vì HTML string
          $thumbnail_url = '';
          if (has_post_thumbnail($post_id)) {
            $thumbnail_url = wp_get_attachment_image_url(get_post_thumbnail_id($post_id), 'full');
          }

          $permalink = get_permalink($post_id);
          if ($cur_lang && $cur_lang !== 'vi' && function_exists('mona_localize_url')) {
            $permalink = mona_localize_url($permalink, $cur_lang);
          }

          $response['data']['items'][] = [
            'id'          => $post_id,
            'name'        => get_field('project_title_short') ?: get_the_title($post_id),
            'description' => wp_strip_all_tags(get_the_excerpt($post_id)),
            'thumbnail'   => $thumbnail_url,
            'permalink'   => $permalink,
            'date'        => get_the_date('d/m/Y', $post_id),
          ];
        }

        wp_reset_postdata();

        // Thêm pagination info
        $response['data']['totalItem'] = count($response['data']['items']);
        $response['data']['totalPage'] = max(1, ceil(count($response['data']['items']) / $posts_per_page));
        $response['data']['pageIndex'] = $paged;
        $response['data']['pageIndex'] = $paged;
      } else {
        $response['data']['items'] = [];
        $response['data']['totalItem'] = 0;
        $response['data']['totalPage'] = 1;
        $response['data']['pageIndex'] = 1;
      }

      wp_send_json($response);
    } catch (\Throwable $th) {
      wp_send_json([
        'success' => false,
        'errors' => [
          __('Đã xảy ra sự cố khi tải danh mục dự án', 'monamedia'),
        ],
      ]);
    }

    wp_die();
  }
}
add_action('wp_ajax_mona_ajax_get_project_categories', 'mona_ajax_get_project_categories');
add_action('wp_ajax_nopriv_mona_ajax_get_project_categories', 'mona_ajax_get_project_categories');

// AJAX: Trả về danh sách các danh mục dự án (taxonomy: danh-muc-du-an)
if (! function_exists('mona_ajax_get_project_category_list')) {
  function mona_ajax_get_project_category_list()
  {
    try {
      if (! empty($_POST['lang'])) {
        do_action('wpml_switch_language', sanitize_key($_POST['lang']));
      }

      $taxonomy = 'danh-muc-du-an';

      $terms = get_terms([
        'taxonomy'   => $taxonomy,
        'hide_empty' => true,
        'meta_key'   => 'term_order_index',
        'orderby'    => 'meta_value_num',
        'order'      => 'ASC',
      ]);

      $cur_lang = ! empty($_POST['lang']) ? sanitize_key($_POST['lang']) : apply_filters('wpml_current_language', null);
      $items = [];
      $seen = [];
      if (! is_wp_error($terms) && ! empty($terms)) {
        foreach ($terms as $term) {
          if (! mona_term_in_lang($term->slug, $cur_lang)) continue;
          // WPML can return the same term multiple times over admin-ajax — dedupe.
          if (isset($seen[$term->slug])) continue;
          $seen[$term->slug] = true;
          $items[] = [
            'id'    => absint($term->term_id),
            'name'  => $term->name,
            'slug'  => $term->slug,
            'count' => absint($term->count),
          ];
        }
      }

      wp_send_json([
        'success' => true,
        'data'    => [ 'items' => $items ],
      ]);
    } catch (\Throwable $th) {
      wp_send_json([
        'success' => false,
        'errors'  => [ __('Đã xảy ra sự cố khi lấy danh mục dự án', 'monamedia') ],
      ]);
    }

    wp_die();
  }
}
add_action('wp_ajax_mona_ajax_get_project_category_list', 'mona_ajax_get_project_category_list');
add_action('wp_ajax_nopriv_mona_ajax_get_project_category_list', 'mona_ajax_get_project_category_list');
