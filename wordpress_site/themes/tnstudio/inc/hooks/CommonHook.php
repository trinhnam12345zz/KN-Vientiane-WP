<?php
if (! defined('ABSPATH')) {
    die;
}

/**
 * Localize internal URLs stored in ACF link/url/page_link fields (e.g. the
 * "view more" buttons) to the current language, so they point to the translated
 * page instead of the Vietnamese original. Default language keeps its URLs.
 */
function mona_localize_url($url)
{
    if (empty($url) || ! is_string($url)) {
        return $url;
    }
    if (preg_match('/^(#|mailto:|tel:|javascript:)/i', $url)) {
        return $url;
    }
    $host   = wp_parse_url(home_url(), PHP_URL_HOST);
    $u_host = wp_parse_url($url, PHP_URL_HOST);
    if ($u_host && $u_host !== $host) {
        return $url; // external link
    }
    $cur = function_exists('pll_current_language') ? pll_current_language('slug') : apply_filters('wpml_current_language', null);
    $def = function_exists('pll_default_language') ? pll_default_language('slug') : apply_filters('wpml_default_language', null);
    if (! $cur || ! $def || $cur === $def) {
        return $url; // default language: keep as-is
    }
    // true => resolve the target object and return its translation's clean URL.
    $out = apply_filters('wpml_permalink', $url, $cur, true);
    if ($out && $out !== $url) {
        return $out;
    }
    // Fallback: resolve the URL to a post ourselves (handles missing trailing
    // slash), then return the translation's permalink.
    $pid = url_to_postid($url);
    if (! $pid) {
        $pid = url_to_postid(trailingslashit($url));
    }
    if ($pid) {
        if (function_exists('pll_get_post')) {
            $tr = pll_get_post($pid, $cur);
            if ($tr) {
                return get_permalink($tr);
            }
        }
        $tr = apply_filters('wpml_object_id', $pid, get_post_type($pid), false, $cur);
        if ($tr) {
            return get_permalink($tr);
        }
    }
    return $out ?: $url;
}

add_filter('acf/format_value/type=url', function ($value) {
    return mona_localize_url($value);
}, 20);

add_filter('acf/format_value/type=page_link', function ($value) {
    if (is_array($value)) {
        return array_map('mona_localize_url', $value);
    }
    return mona_localize_url($value);
}, 20);

add_filter('acf/format_value/type=link', function ($value) {
    if (is_array($value) && ! empty($value['url'])) {
        $value['url'] = mona_localize_url($value['url']);
    }
    return $value;
}, 20);

/**
 * Render the current-language translation of a Contact Form 7 form. The theme
 * embeds forms by shortcode (source form); on translated pages swap to the
 * linked translation. Replaces the "Contact Form 7 Multilingual" glue plugin.
 */
add_filter('wpcf7_contact_form', function ($contact_form) {
    static $swapping = false;
    if ($swapping || is_admin() || ! $contact_form) {
        return $contact_form;
    }
    $cur = apply_filters('wpml_current_language', null);
    $def = apply_filters('wpml_default_language', null);
    if (! $cur || ! $def || $cur === $def) {
        return $contact_form;
    }
    $id = method_exists($contact_form, 'id') ? $contact_form->id() : 0;
    if (! $id) {
        return $contact_form;
    }
    $tr = apply_filters('wpml_object_id', $id, 'wpcf7_contact_form', false, $cur);
    if ($tr && $tr != $id && function_exists('wpcf7_contact_form')) {
        $swapping = true;
        $translated = wpcf7_contact_form($tr);
        $swapping = false;
        if ($translated) {
            return $translated;
        }
    }
    return $contact_form;
}, 10, 1);

/**
 * Ngôn ngữ chưa có bản dịch form thì dựng lại form của ngôn ngữ mặc định.
 *
 * Hiện có 16 form CF7 chia đều cho vi/en/lo/ko, riêng tiếng Trung KHÔNG có form
 * nào. WPML lọc truy vấn `wpcf7_contact_form` theo ngôn ngữ đang xem, nên trên
 * trang tiếng Trung, Contact Form 7 không tìm ra form nào — kể cả bản gốc tiếng
 * Việt — và in thẳng ra dòng "Error: Contact form not found." giữa trang.
 *
 * Filter `wpcf7_contact_form` ở trên không cứu được vì lỗi xảy ra TRƯỚC nó: form
 * còn chưa tìm thấy thì chưa có gì để đổi.
 *
 * Ở đây bắt đúng lúc shortcode đã trả về thông báo lỗi, tạm chuyển về ngôn ngữ
 * mặc định rồi dựng lại. Khách vẫn gửi được liên hệ, chỉ là nhãn form bằng tiếng
 * Việt — vẫn hơn một dòng lỗi tiếng Anh.
 *
 * Đây là giải pháp tạm. Cách đúng là tạo bản dịch form cho ngôn ngữ đó; khi có
 * rồi thì nhánh này không chạy nữa vì shortcode tìm thấy form ngay từ đầu.
 */
add_filter('do_shortcode_tag', function ($output, $tag, $attr, $m) {
    static $retrying = false;

    if ($retrying || $tag !== 'contact-form-7' || is_admin()) {
        return $output;
    }

    if (strpos($output, 'contact-form-not-found') === false) {
        return $output;
    }

    $cur = apply_filters('wpml_current_language', null);
    $def = apply_filters('wpml_default_language', null);

    if (! $cur || ! $def || $cur === $def) {
        return $output;
    }

    $retrying = true;

    // 1. Có form khai báo riêng cho ngôn ngữ này thì dùng đúng form đó.
    //    Bảng ở inc/functions/LangIdMap.php, khoá theo mã băm trong shortcode.
    $mapped = null;
    if (function_exists('mona_cf7_lang_map') && ! empty($attr['id'])) {
        $mapped = mona_cf7_lang_map()[(string) $attr['id']][$cur] ?? null;
    }

    if ($mapped) {
        $translated = do_shortcode('[contact-form-7 id="' . (int) $mapped . '"]');

        if (strpos($translated, 'contact-form-not-found') === false) {
            $retrying = false;
            return $translated;
        }
    }

    // 2. Chưa khai báo thì dựng lại bằng form của ngôn ngữ mặc định, để khách
    //    vẫn gửi được liên hệ thay vì nhìn một dòng lỗi tiếng Anh.
    do_action('wpml_switch_language', $def);
    $retry = do_shortcode($m[0]);
    do_action('wpml_switch_language', $cur);
    $retrying = false;

    return strpos($retry, 'contact-form-not-found') === false ? $retry : $output;
}, 10, 4);

/**
 * Automatically switch ACF option pages to the current language's options.
 * Matches Polylang current language on both frontend and admin.
 */
add_filter('acf/validate_post_id', function ($post_id) {
    if ($post_id === 'option' || $post_id === 'options') {
        $cur = function_exists('pll_current_language') ? pll_current_language('slug') : apply_filters('wpml_current_language', null);
        $def = function_exists('pll_default_language') ? pll_default_language('slug') : 'vi';
        if ($cur && $def && $cur !== $def) {
            return 'options_' . $cur;
        }
    }
    return $post_id;
}, 5, 1);

/**
 * Secondary post queries deduplication & filtering.
 * language. As a global safety net, de-duplicate and drop wrong-language posts
 * on the front end. No-op when WPML is inactive or already filtering correctly.
 */
add_action('pre_get_posts', function ($query) {
    if (is_admin() || ! ($query instanceof WP_Query) || ! $query->is_main_query()) {
        return;
    }

    $cur = function_exists('pll_current_language') ? pll_current_language('slug') : apply_filters('wpml_current_language', null);
    if (! $cur) {
        return;
    }

    // 1. Resolve front page translations for language root paths (/en/, /lo/, /zh-hant/, /ko/)
    $req_path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    $is_lang_root = in_array($req_path, ['', 'en', 'lo', 'zh-hant', 'ko', 'zh'], true);
    $front_id = (int) get_option('page_on_front');

    if ($front_id && $is_lang_root) {
        $tr_id = function_exists('pll_get_post') ? pll_get_post($front_id, $cur) : apply_filters('wpml_object_id', $front_id, 'page', false, $cur);
        if ($tr_id) {
            $query->set('page_id', (int) $tr_id);
            $query->set('post_type', 'page');
            $query->set('p', 0);
            $query->is_page = true;
            $query->is_singular = true;
            $query->is_home = false;
        }
        return;
    }

    // 2. Resolve inner pages by pagename across languages (including page_for_posts 'tin-tuc')
    $pagename = $query->get('pagename');
    if (empty($pagename) && ! empty($query->query_vars['name'])) {
        $pagename = $query->query_vars['name'];
    }

    if (! empty($pagename)) {
        $page = get_page_by_path($pagename);
        if ($page) {
            $blog_page_id = (int) get_option('page_for_posts', 16);
            $blog_ids = [16, 1680, 1555, 1681, 1682];
            if (function_exists('pll_get_post_translations') && $blog_page_id) {
                $tr_blog = pll_get_post_translations($blog_page_id);
                if (is_array($tr_blog)) {
                    $blog_ids = array_unique(array_merge($blog_ids, array_values($tr_blog)));
                }
            }

            if (in_array((int) $page->ID, $blog_ids, true) || $pagename === 'tin-tuc') {
                $tr_id = function_exists('pll_get_post') ? pll_get_post($page->ID, $cur) : apply_filters('wpml_object_id', $page->ID, 'page', false, $cur);
                $target_id = $tr_id ?: $page->ID;
                $query->set('post_type', 'post');
                $query->set('page_id', 0);
                $query->set('p', 0);
                $query->set('pagename', '');
                $query->set('name', '');
                $query->is_page = false;
                $query->is_singular = false;
                $query->is_home = true;
                $query->is_posts_page = true;
                $query->queried_object = get_post($target_id);
                $query->queried_object_id = (int) $target_id;
                return;
            }

            $tr_id = function_exists('pll_get_post') ? pll_get_post($page->ID, $cur) : apply_filters('wpml_object_id', $page->ID, 'page', false, $cur);
            $target_id = $tr_id ?: $page->ID;
            $query->set('page_id', (int) $target_id);
            $query->set('post_type', 'page');
            $query->set('p', 0);
            $query->set('pagename', '');
            $query->is_page = true;
            $query->is_singular = true;
            $query->is_home = false;
        }
    }

    // 3. Resolve single posts and custom post types across languages
    $name = $query->get('name');
    if (! empty($name) && empty($query->get('page_id'))) {
        $pt = $query->get('post_type');
        if (empty($pt)) {
            if (! empty($query->get('du-an'))) {
                $pt = 'du-an';
            } elseif (! empty($query->get('linh-vuc-hoat-dong'))) {
                $pt = 'linh-vuc-hoat-dong';
            } elseif (! empty($query->get('tuyen-dung'))) {
                $pt = 'tuyen-dung';
            }
        }

        global $wpdb;
        $found = null;
        if (! empty($pt) && $pt !== 'any') {
            if (is_array($pt)) {
                $placeholders = implode(',', array_fill(0, count($pt), '%s'));
                $params = array_merge([$name], $pt);
                $found = $wpdb->get_row($wpdb->prepare(
                    "SELECT ID, post_type FROM {$wpdb->posts} WHERE post_name = %s AND post_type IN ($placeholders) AND post_status = 'publish' LIMIT 1",
                    ...$params
                ));
            } else {
                $found = $wpdb->get_row($wpdb->prepare(
                    "SELECT ID, post_type FROM {$wpdb->posts} WHERE post_name = %s AND post_type = %s AND post_status = 'publish' LIMIT 1",
                    $name,
                    $pt
                ));
            }
        }

        if (! $found) {
            $found = $wpdb->get_row($wpdb->prepare(
                "SELECT ID, post_type FROM {$wpdb->posts} WHERE post_name = %s AND post_status = 'publish' LIMIT 1",
                $name
            ));
        }

        if ($found) {
            $tr_id = function_exists('mona_get_translated_post_id') ? mona_get_translated_post_id($found->ID, $cur) : (function_exists('pll_get_post') ? pll_get_post($found->ID, $cur) : apply_filters('wpml_object_id', $found->ID, $found->post_type, false, $cur));
            $target_id = $tr_id ?: $found->ID;
            clean_object_term_cache($target_id, $found->post_type);
            $query->set('p', (int) $target_id);
            $query->set('name', '');
            $query->set('post_type', $found->post_type);
            $query->is_single = true;
            $query->is_singular = true;
            $query->queried_object = get_post($target_id);
            $query->queried_object_id = (int) $target_id;
        }
    }
}, 1);

/**
 * Ensure correct template is ALWAYS loaded for the homepage and news archive
 */
add_filter('template_include', function ($template) {
    if (is_admin()) {
        return $template;
    }

    $front_id = (int) get_option('page_on_front');
    $current_id = get_queried_object_id();

    $home_ids = [14, 1683, 1685, 1486, 1684];
    if (function_exists('pll_get_post_translations') && $front_id) {
        $tr = pll_get_post_translations($front_id);
        if (is_array($tr)) {
            $home_ids = array_merge($home_ids, array_values($tr));
        }
    }

    if (mona_is_homepage() || in_array($current_id, $home_ids, true)) {
        $front_template = locate_template('front-page.php');
        if ($front_template) {
            return $front_template;
        }
    }

    $blog_page_id = (int) get_option('page_for_posts', 16);
    $blog_ids = [16, 1680, 1555, 1681, 1682];
    if (function_exists('pll_get_post_translations') && $blog_page_id) {
        $tr_blog = pll_get_post_translations($blog_page_id);
        if (is_array($tr_blog)) {
            $blog_ids = array_unique(array_merge($blog_ids, array_values($tr_blog)));
        }
    }

    global $wp_query;
    if ($wp_query->is_home() || in_array($current_id, $blog_ids, true)) {
        $index_template = locate_template('index.php');
        if ($index_template) {
            return $index_template;
        }
    }

    return $template;
}, 99);

/**
 * Prevent WordPress canonical redirect from sending /{lang}/ → /{lang}/trang-chu/.
 *
 * When visiting /lo/, /en/, /ko/, /zh-hant/, our pre_get_posts sets page_id to
 * the translated homepage. WordPress then sees that the current URL doesn't
 * match the post's permalink (/lo/trang-chu/) and issues a 301 redirect.
 * This breaks the clean language root URL experience.
 */
add_filter('redirect_canonical', function ($redirect_url, $requested_url) {
    if (is_admin()) {
        return $redirect_url;
    }

    $req_path = trim(parse_url($requested_url, PHP_URL_PATH), '/');
    $lang_roots = ['', 'en', 'lo', 'zh-hant', 'ko', 'zh'];

    if (in_array($req_path, $lang_roots, true)) {
        return false; // Cancel the redirect
    }

    return $redirect_url;
}, 10, 2);

add_action('wp', function () {
    global $wp_query;
    if ($wp_query instanceof WP_Query) {
        if (! empty($wp_query->posts[0]) && $wp_query->is_singular()) {
            $wp_query->queried_object = $wp_query->posts[0];
            $wp_query->queried_object_id = (int) $wp_query->posts[0]->ID;
        }
        if ($wp_query->is_404 && ! empty($wp_query->get('page_id'))) {
            $p = get_post($wp_query->get('page_id'));
            if ($p && $p->post_type === 'page') {
                $wp_query->is_404 = false;
                $wp_query->is_page = true;
                $wp_query->is_singular = true;
                $wp_query->queried_object = $p;
                $wp_query->queried_object_id = (int) $p->ID;
                status_header(200);
            }
        }
    }
}, 1);

add_filter('the_posts', function ($posts, $query) {
    if (is_admin() || empty($posts)) {
        return $posts;
    }
    // Leave the main query (WPML/Polylang filters it) and menu queries alone.
    if ($query instanceof WP_Query && $query->is_main_query()) {
        return $posts;
    }
    if ($query instanceof WP_Query && $query->get('post_type') === 'nav_menu_item') {
        return $posts;
    }
    $cur = apply_filters('wpml_current_language', null);
    if (empty($cur)) {
        return $posts;
    }
    $seen = [];
    $out  = [];
    foreach ($posts as $p) {
        if (isset($seen[$p->ID])) {
            continue;
        }
        $pl = apply_filters('wpml_post_language_details', null, $p->ID);
        if (is_array($pl) && ! empty($pl['language_code']) && $pl['language_code'] !== $cur) {
            continue;
        }
        $seen[$p->ID] = true;
        $out[]        = $p;
    }
    return $out;
}, 20, 2);

/**
 * Same safety net for the translatable CPT taxonomies: WPML can return every
 * language's copy of a term in secondary get_terms() calls (project category
 * tabs, recruitment filters). Keep only current-language terms + de-duplicate.
 * Uses the import slug convention (translated terms end with -{lang}).
 */
add_filter('get_terms', function ($terms, $taxonomies, $args = [], $query = null) {
    if (is_admin() || empty($terms) || ! is_array($terms)) {
        return $terms;
    }
    $targets = ['danh-muc-du-an', 'vi-tri-tuyen-dung', 'dia-diem-tuyen-dung'];
    if (empty(array_intersect((array) $taxonomies, $targets))) {
        return $terms;
    }
    if (isset($args['lang']) && $args['lang'] === '') {
        return $terms;
    }
    $cur = !empty($args['lang']) ? $args['lang'] : (function_exists('pll_current_language') ? pll_current_language() : apply_filters('wpml_current_language', null));
    if (empty($cur)) {
        return $terms;
    }
    $seen = [];
    $out  = [];
    foreach ($terms as $t) {
        if (! is_object($t) || ! isset($t->term_id)) {
            $out[] = $t;
            continue;
        }

        if (function_exists('pll_get_term_language')) {
            $t_lang = pll_get_term_language($t->term_id);
            if ($t_lang && $t_lang !== $cur) {
                continue;
            }
        }

        $check_langs = [$cur];
        if ($cur === 'zh-hant') {
            $check_langs[] = 'zh';
        }

        $lang_ok = true;
        if ($cur === 'vi') {
            $lang_ok = ! preg_match('/-(en|lo|ko|zh|zh-hant)$/', $t->slug);
        } else {
            $matched = false;
            foreach ($check_langs as $cl) {
                if (substr($t->slug, -strlen('-' . $cl)) === '-' . $cl) {
                    $matched = true;
                    break;
                }
            }
            $lang_ok = $matched;
        }

        if (! $lang_ok || isset($seen[$t->name])) {
            continue;
        }
        $seen[$t->name] = true;
        $out[]          = $t;
    }
    return $out;
}, 20, 4);

// After setup theme
add_action('after_setup_theme', function () {
    // register menu
    register_nav_menus(
        [
            'primary-menu' => __('Primary Menu (Menu Chính)', 'tnstudio'),
        ]
    );
});

/**
 * Add param to admin url when use ajax
 * 
 * @param string $url The complete admin area URL including scheme and path.
 * @param string $path Path relative to the admin area URL. Blank string if no path is specified.
 * @param int|null $blog_id Site ID, or null for the current site
 * 
 * @return string
 */
add_filter('admin_url', function ($url, $path, $blog_id) {
    if ($path === 'admin-ajax.php' && ! is_admin()) {
        // Add a query parameter with a value to avoid servers rejecting bare query keys
        $url = add_query_arg('mona-ajax', '1', $url);
    }

    return $url;
}, 999, 3);

// Register css
add_action('wp_enqueue_scripts', function () {
    if (is_404()) {
        wp_enqueue_style('mona-404', MONA_THEME_PATH_URI . '/assets/css/404.css', [], MONA_THEME_VERSION);
    }

    // Font CenturySchoolbookBT
    // wp_enqueue_style('mona-font-CenturySchoolbookBT', MONA_SITE_TEMPLATE_URL . '/assets/fonts/SFU-CenturySchoolbookBT/stylesheet.css');

    /**
     * Hook: mona_before_common_css
     */
    do_action('mona_before_common_css');

    // Common
    // wp_enqueue_style('mona-common', MONA_SITE_TEMPLATE_URL . '/assets/css/common.css');

    // Main
    wp_enqueue_style('mona-main-style', get_stylesheet_uri(), [], MONA_THEME_VERSION);

    // Mona custom
    wp_enqueue_style('mona-custom', MONA_THEME_PATH_URI . '/assets/css/mona-custom.css', [], MONA_THEME_VERSION);

    /**
     * Hook: mona_after_common_css
     */
    do_action('mona_after_common_css');
}, 10);

// Mona frontend js
add_action('wp_enqueue_scripts', function () {
    // Mona frontend
    wp_enqueue_script(
        'mona-frontend',
        MONA_THEME_PATH_URI . '/assets/scripts/mona-frontend.js',
        array('jquery'),
        MONA_THEME_VERSION,
        array(
            'in_footer' => true,
        )
    );

    $params = apply_filters('mona_ajax_params', [
        'siteURL'   => get_site_url(),
        'ajaxURL'   => admin_url('admin-ajax.php'),
        'ajaxNonce' => wp_create_nonce('mona-ajax-security'),
        'lang'      => apply_filters('wpml_current_language', null),
        'i18n'      => [
            'all'           => __('Tất cả', 'monamedia'),
            'projectDetail' => __('Dự án chi tiết', 'monamedia'),
            'copied'        => __('Đã sao chép', 'monamedia'),
        ],
        'messages'  => [
            'list_empty'  => __('Không có dự án nào', 'monamedia'),
            'error_title' => __('Lỗi', 'monamedia'),
        ],
    ]);

    wp_localize_script('mona-frontend', 'mona_params', $params);
}, 10);

// Public REST endpoint to return project categories (useful if admin-ajax POSTs are blocked by server/WAF)
add_action('rest_api_init', function () {
    register_rest_route('mona/v1', '/project-categories', [
        'methods'  => 'GET',
        'callback' => function ($request) {
            // Switch to the requested language so terms come back translated.
            $lang = $request->get_param('lang');
            if (! empty($lang)) {
                do_action('wpml_switch_language', sanitize_key($lang));
            }

            $taxonomy = 'danh-muc-du-an';

            $terms = get_terms([
                'taxonomy'   => $taxonomy,
                'hide_empty' => false,
                'meta_key'   => 'term_order_index',
                'orderby'    => 'meta_value_num',
                'order'      => 'ASC',
            ]);

            $cur_lang = $request->get_param('lang') ? sanitize_key($request->get_param('lang')) : apply_filters('wpml_current_language', null);
            $items = [];
            if (! is_wp_error($terms) && ! empty($terms)) {
                foreach ($terms as $term) {
                    if (function_exists('mona_term_in_lang') && ! mona_term_in_lang($term->slug, $cur_lang)) continue;
                    $items[] = [
                        'id'    => absint($term->term_id),
                        'name'  => $term->name,
                        'slug'  => $term->slug,
                        'count' => absint($term->count),
                    ];
                }
            }

            return rest_ensure_response([
                'success' => true,
                'data'    => ['items' => $items],
            ]);
        },
        'permission_callback' => '__return_true',
    ]);
});

add_action('wp_enqueue_scripts', function () {
    if (is_page_template('page-template/template-project.php')) {
        wp_enqueue_script(
            'mona-project',
            MONA_THEME_PATH_URI . '/assets/scripts/pages/project.js',
            array('jquery', 'mona-frontend'),
            MONA_THEME_VERSION,
            array(
                'in_footer' => true,
            )
        );
    }

    if (is_home()) {
        wp_enqueue_script(
            'mona-posts',
            MONA_THEME_PATH_URI . '/assets/scripts/pages/posts.js',
            array('jquery', 'mona-frontend'),
            MONA_THEME_VERSION,
            array(
                'in_footer' => true,
            )
        );
    }
}, 10);

// Optimize script attributes (module + defer)
// add_filter('script_loader_tag', function ($tag, $handle, $src) {
//     $module_handles = apply_filters('mona_script_to_module', [
//         'mona-main',
//         'mona-frontend',
//     ]);

//     if (in_array($handle, $module_handles) && strpos($tag, 'type=') === false) {
//         $tag = str_replace('<script', '<script type="module"', $tag);
//     }

//     $defer_handles = [
//         'jquery',
//         'jquery-core',
//         'jquery-migrate',
//         'swv',
//         'contact-form-7',
//         'wpcf7-recaptcha',
//         'google-recaptcha',
//         'maps-points',
//         'jquery-dgwt-wcas',
//     ];

//     if (in_array($handle, $defer_handles) && strpos($tag, 'defer') === false && strpos($tag, 'async') === false) {
//         $tag = str_replace(' src', ' defer src', $tag);
//     }

//     return $tag;
// }, 10, 3);

add_filter('script_loader_tag', function ($tag, $handle) {
    // Handlers
    $handlers = apply_filters('mona_script_to_module', [
        'mona-main',
        'mona-frontend',
        'mona-project',
    ]);

    if (in_array($handle, $handlers)) {
        $tag = str_replace('<script', '<script type="module"', $tag);
    }

    return $tag;
}, 10, 2);

// Preconnect google font
add_action('wp_head', function () {
?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
<?php
}, 1);

add_filter('body_class', function (array $classes): array {
    if (!is_page_template('page-template/template-project.php')) {
        $classes[] = 'dark-hd';
    }

    if (mona_is_homepage()) {
        $classes[] = 'p-home';
    } elseif (is_page_template('page-template/template-about.php')) {
        $classes[] = 'p-about';
    } elseif (is_page_template('page-template/template-areas-activity.php') || is_singular('linh-vuc-hoat-dong') || is_page_template('page-template/template-project.php') || is_singular('du-an')) {
        $classes[] = 'p-field';
    } elseif (is_singular('tuyen-dung') || is_page_template('page-template/template-recruits.php') || is_post_type_archive('tuyen-dung')) {
        $classes[] = 'p-recruit';
    } elseif (is_page_template('page-template/template-contact.php')) {
        $classes[] = 'p-contact';
    } elseif (is_page_template('page-template/template-stable.php')) {
        $classes[] = 'p-stable';
    } elseif (is_page_template('page-template/template-origanization.php')) {
        $classes[] = 'p-about-organization';
    } elseif (is_search()) {
        $classes[] = 'p-search';
    } elseif (is_singular('tuyen-dung')) {
        $classes[] = 'p-recruit-detail';
    } elseif (is_category() || is_tag()) {
        $classes[] = 'p-category p-news';
    } elseif (is_home()) {
        $classes[] = 'p-post p-news';
    } else {
        $classes[] = 'p-post-detail';
    }

    return $classes;
}, 10);

/**
 * =============================================================
 * AVIF & WEBP MIME TYPE SUPPORT
 * Allow AVIF and WebP uploads + fix MIME type serving
 * =============================================================
 */

// Allow AVIF upload in Media Library
add_filter('upload_mimes', function ($mimes) {
    $mimes['avif'] = 'image/avif';
    $mimes['webp'] = 'image/webp';
    return $mimes;
});

// Fix AVIF MIME type detection
add_filter('wp_check_filetype_and_ext', function ($data, $file, $filename, $mimes) {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if ($ext === 'avif') {
        $data['ext'] = 'avif';
        $data['type'] = 'image/avif';
    }
    if ($ext === 'webp') {
        $data['ext'] = 'webp';
        $data['type'] = 'image/webp';
    }
    return $data;
}, 10, 4);

// Send correct headers for AVIF files (for Nginx passthrough)
add_action('init', function () {
    if (isset($_SERVER['REQUEST_URI']) && preg_match('/\.avif$/i', $_SERVER['REQUEST_URI'])) {
        header('Content-Type: image/avif');
    }
});
/**
 * Optimize custom logo for LCP performance.
 * - Adds fetchpriority="high" for faster loading
 * - Removes loading="lazy" to prevent delayed rendering
 *
 * @param array $attrs Custom logo image attributes.
 * @return array Modified attributes.
 */
function mona_optimize_custom_logo_attrs($attrs)
{
    $attrs['fetchpriority'] = 'high';
    unset($attrs['loading']); // Remove lazy loading
    // Add explicit dimensions to prevent CLS
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $meta = wp_get_attachment_metadata($custom_logo_id);
        if ($meta && isset($meta['width']) && isset($meta['height'])) {
            $attrs['width'] = $meta['width'];
            $attrs['height'] = $meta['height'];
        }
    }

    return $attrs;
}
add_filter('get_custom_logo_image_attributes', 'mona_optimize_custom_logo_attrs');

/**
 * =============================================================
 * CONTACT FORM 7 - DEFER LOADING
 * Defer CF7 assets to improve LCP - don't block initial render
 * but keep localized data intact for AJAX submission
 * =============================================================
 */

// Make non-critical CSS non-blocking
add_filter('style_loader_tag', function ($tag, $handle) {
    // Skip if in admin
    if (is_admin())
        return $tag;

    // Non-critical CSS that can be loaded async
    $async_styles = [
        // Theme styles (handle from frontpage.php)
        'frontpage-style',
        // Contact Form 7
        'contact-form-7',
        'wpcf7-recaptcha',
        'powertip',
        'maps-points',
    ];

    if (in_array($handle, $async_styles)) {
        // Use media="print" trick to make CSS non-blocking
        if (strpos($tag, "media='all'") !== false) {
            return str_replace(
                "media='all'",
                "media='print' onload=\"this.media='all'\"",
                $tag
            );
        }

        if (strpos($tag, 'media="all"') !== false) {
            return str_replace('media="all"', 'media="print" onload="this.media=\'all\'"', $tag);
        }
    }

    return $tag;
}, 10, 2);


/**
 * =============================================================
 * WP-HOOKS & WP-I18N OPTIMIZATION
 * Preload scripts in head + move execution to footer
 * This reduces critical path latency without breaking CF7
 * =============================================================
 */

// Preload wp-hooks and wp-i18n in head
add_action('wp_head', function () {
    if (is_admin())
        return;

    // Only preload if CF7 is active (these scripts are loaded by CF7)
    if (!defined('WPCF7_VERSION'))
        return;

    $wp_includes = includes_url('js/dist/');
    echo '<link rel="preload" href="' . esc_url($wp_includes . 'hooks.min.js') . '" as="script">' . "\n";
    echo '<link rel="preload" href="' . esc_url($wp_includes . 'i18n.min.js') . '" as="script">' . "\n";
}, 1);

// Move wp-hooks and wp-i18n to footer (group 1 = footer)
add_action('wp_enqueue_scripts', function () {
    if (is_admin())
        return;

    // Move to footer - keeps inline scripts working but moves out of critical path
    wp_script_add_data('wp-hooks', 'group', 1);
    wp_script_add_data('wp-i18n', 'group', 1);
}, 100);

// jquery-migrate remove keep jquery core
add_filter('wp_default_scripts', function ($scripts) {
    if (isset($scripts->registered['jquery'])) {
        $scripts->registered['jquery']->deps = array_diff($scripts->registered['jquery']->deps, array('jquery-migrate'));
    }
});

add_action('wp_enqueue_scripts', function () {
    $handles = [
        'css' => [
            // 'mona-theme-style' => get_stylesheet_uri(),
            'mona-open_sans' => MONA_SITE_TEMPLATE_URL . '/assets/fonts/Open_sans/stylesheet.css',
            // 'mona-Inter' => MONA_SITE_TEMPLATE_URL . '/assets/fonts/Inter/stylesheet.css',
            'mona-TrajanPro3' => MONA_SITE_TEMPLATE_URL . '/assets/fonts/TrajanPro3/stylesheet.css',
            'mona-aos' => MONA_SITE_TEMPLATE_URL . '/assets/js/library/aos/aos.css',
        ],
        'js' => [
            'mona-aos' => MONA_SITE_TEMPLATE_URL . '/assets/js/library/aos/aos.js',
            'mona-jquery.matchHeight' => MONA_SITE_TEMPLATE_URL . '/assets/js/library/matchHeight/jquery.matchHeight.js',
        ],
    ];

    if (mona_is_homepage()) {
        $handles['css']['mona-swiper'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/swiper/swiper-bundle.min.css';
        $handles['css']['mona-fullpage'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/fullpagejs/fullpage.min.css';
        $handles['css']['mona-select'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/select2/select2.min.css';
        // Popup video ở section giới thiệu cần thư viện modal (trước đây chỉ trang giới thiệu nạp)
        $handles['css']['mona-jquery.modal'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/modal/jquery.modal.min.css';
        $handles['css']['mona-common'] = MONA_SITE_TEMPLATE_URL . '/assets/css/common.css';
        $handles['css']['mona-home'] = MONA_SITE_TEMPLATE_URL . '/assets/css/home.css';

        $handles['js']['mona-swiper'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/swiper/swiper-bundle.min.js';
        $handles['js']['mona-select'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/select2/select2.min.js';
        $handles['js']['mona-jquery.modal'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/modal/jquery.modal.min.js';
        $handles['js']['mona-fullpage'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/fullpagejs/jquery.fullPage.min.js';
    } elseif (is_page_template('page-template/template-about.php')) {
        $handles['css']['mona-swiper'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/swiper/swiper-bundle.min.css';
        $handles['css']['mona-jquery.modal'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/modal/jquery.modal.min.css';
        $handles['css']['mona-common'] = MONA_SITE_TEMPLATE_URL . '/assets/css/common.css';
        $handles['css']['mona-about'] = MONA_SITE_TEMPLATE_URL . '/assets/css/about.css';

        $handles['js']['mona-swiper'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/swiper/swiper-bundle.min.js';
        $handles['js']['mona-jquery.modal'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/modal/jquery.modal.min.js';
    } elseif (is_page_template('page-template/template-areas-activity.php')) {
        $handles['css']['mona-select'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/select2/select2.min.css';
        $handles['css']['mona-swiper'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/swiper/swiper-bundle.min.css';
        $handles['css']['mona-common'] = MONA_SITE_TEMPLATE_URL . '/assets/css/common.css';
        $handles['css']['mona-field'] = MONA_SITE_TEMPLATE_URL . '/assets/css/field.css';

        $handles['js']['mona-swiper'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/swiper/swiper-bundle.min.js';
        $handles['js']['mona-select'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/select2/select2.min.js';
    } elseif (is_singular('linh-vuc-hoat-dong') || is_singular('du-an')) {
        $handles['css']['mona-select'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/select2/select2.min.css';
        $handles['css']['mona-swiper'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/swiper/swiper-bundle.min.css';
        $handles['css']['mona-common'] = MONA_SITE_TEMPLATE_URL . '/assets/css/common.css';
        $handles['css']['mona-field-detail'] = MONA_SITE_TEMPLATE_URL . '/assets/css/field.css';
        $handles['css']['mona-news-detail'] = MONA_SITE_TEMPLATE_URL . '/assets/css/news.css';

        $handles['js']['mona-swiper'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/swiper/swiper-bundle.min.js';
        $handles['js']['mona-select'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/select2/select2.min.js';
    } elseif (is_page_template('page-template/template-project.php')) {
        $handles['css']['mona-select'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/select2/select2.min.css';
        $handles['css']['mona-swiper'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/swiper/swiper-bundle.min.css';
        $handles['css']['mona-common'] = MONA_SITE_TEMPLATE_URL . '/assets/css/common.css';
        $handles['css']['mona-field-detail'] = MONA_SITE_TEMPLATE_URL . '/assets/css/field.css';

        $handles['js']['mona-swiper'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/swiper/swiper-bundle.min.js';
        $handles['js']['mona-select'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/select2/select2.min.js';
    } elseif (is_page_template('page-template/template-recruitment.php')) {
        $handles['css']['mona-swiper'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/swiper/swiper-bundle.min.css';
        $handles['css']['mona-common'] = MONA_SITE_TEMPLATE_URL . '/assets/css/common.css';
        $handles['css']['mona-field-detail'] = MONA_SITE_TEMPLATE_URL . '/assets/css/recruit.css';

        $handles['js']['mona-swiper'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/swiper/swiper-bundle.min.js';
    } elseif (is_singular('tuyen-dung')) {
        $handles['css']['mona-swiper'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/swiper/swiper-bundle.min.css';
        $handles['css']['mona-gallery'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/gallery/lightgallery.min.css';
        $handles['css']['mona-common'] = MONA_SITE_TEMPLATE_URL . '/assets/css/common.css';
        $handles['css']['mona-recruit-detail'] = MONA_SITE_TEMPLATE_URL . '/assets/css/recruit.css';

        $handles['js']['mona-swiper'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/swiper/swiper-bundle.min.js';
        $handles['js']['mona-lightgallery-all'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/gallery/lightgallery-all.min.js';
        $handles['js']['mona-gallery'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/gallery/lightgallery.min.js';
    } elseif (is_page_template('page-template/template-recruits.php') || is_post_type_archive('tuyen-dung')) {
        $handles['css']['mona-select'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/select2/select2.min.css';
        $handles['css']['mona-common'] = MONA_SITE_TEMPLATE_URL . '/assets/css/common.css';
        $handles['css']['mona-recruit'] = MONA_SITE_TEMPLATE_URL . '/assets/css/recruit.css';

        $handles['js']['mona-swiper'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/swiper/swiper-bundle.min.js';
        $handles['js']['mona-select'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/select2/select2.min.js';
    } elseif (is_page_template('page-template/template-stable.php')) {
        $handles['css']['mona-select'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/select2/select2.min.css';
        $handles['css']['mona-swiper'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/swiper/swiper-bundle.min.css';
        $handles['css']['mona-common'] = MONA_SITE_TEMPLATE_URL . '/assets/css/common.css';
        $handles['css']['mona-field-detail'] = MONA_SITE_TEMPLATE_URL . '/assets/css/stable.css';

        $handles['js']['mona-swiper'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/swiper/swiper-bundle.min.js';
        $handles['js']['mona-select'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/select2/select2.min.js';
    } elseif (is_page_template('page-template/template-contact.php')) {
        $handles['css']['mona-select'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/select2/select2.min.css';
        $handles['css']['mona-common'] = MONA_SITE_TEMPLATE_URL . '/assets/css/common.css';
        $handles['css']['mona-contact'] = MONA_SITE_TEMPLATE_URL . '/assets/css/contact.css';

        $handles['js']['mona-swiper'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/swiper/swiper-bundle.min.js';
        $handles['js']['mona-select'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/select2/select2.min.js';
    } elseif (is_category() || is_tag() || is_home() || is_post_type_archive('post')) {
        $handles['css']['mona-common'] = MONA_SITE_TEMPLATE_URL . '/assets/css/common.css';
        $handles['css']['mona-swiper'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/swiper/swiper-bundle.min.css';
        $handles['css']['mona-field'] = MONA_SITE_TEMPLATE_URL . '/assets/css/field.css';
        $handles['css']['mona-news'] = MONA_SITE_TEMPLATE_URL . '/assets/css/news.css';

        $handles['js']['mona-swiper'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/swiper/swiper-bundle.min.js';
    } elseif (is_search()) {
        $handles['css']['mona-common'] = MONA_SITE_TEMPLATE_URL . '/assets/css/common.css';
        $handles['css']['mona-search'] = MONA_SITE_TEMPLATE_URL . '/assets/css/search.css';
    } else {
        $handles['css']['mona-swiper'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/swiper/swiper-bundle.min.css';
        $handles['css']['mona-common'] = MONA_SITE_TEMPLATE_URL . '/assets/css/common.css';
        $handles['css']['mona-field'] = MONA_SITE_TEMPLATE_URL . '/assets/css/field.css';
        $handles['css']['mona-news'] = MONA_SITE_TEMPLATE_URL . '/assets/css/news.css';

        $handles['js']['mona-swiper'] = MONA_SITE_TEMPLATE_URL . '/assets/js/library/swiper/swiper-bundle.min.js';
    }

    // CSS
    // wp_enqueue_style('mona-Anton-Round', MONA_SITE_TEMPLATE_URL . '/assets/fonts/stylesheet.css');
    // wp_enqueue_style('mona-Inter', MONA_SITE_TEMPLATE_URL . '/assets/fonts/Inter/stylesheet.css');
    wp_enqueue_style('mona-open_sans', MONA_SITE_TEMPLATE_URL . '/assets/fonts/Open_sans/stylesheet.css', [], MONA_THEME_VERSION);

    wp_enqueue_style('mona-TrajanPro3', MONA_SITE_TEMPLATE_URL . '/assets/fonts/TrajanPro3/stylesheet.css', [], MONA_THEME_VERSION);
    wp_enqueue_style('mona-aos', MONA_SITE_TEMPLATE_URL . '/assets/js/library/aos/aos.css', [], MONA_THEME_VERSION);

    foreach ($handles['css'] as $handle => $url) {
        wp_enqueue_style($handle, $url, [], MONA_THEME_VERSION, 'all');
    }

    // JS
    wp_add_inline_script('jquery', 'window.$=window.jQuery;');
    wp_enqueue_script('mona-aos', MONA_SITE_TEMPLATE_URL . '/assets/js/library/aos/aos.js', [], MONA_THEME_VERSION, [
        'in_footer' => true,
        'strategy' => 'defer',
    ]);

    wp_enqueue_script('mona-jquery.matchHeight', MONA_SITE_TEMPLATE_URL . '/assets/js/library/matchHeight/jquery.matchHeight.js', [], MONA_THEME_VERSION, [
        'in_footer' => true,
        'strategy' => 'defer',
    ]);

    foreach ($handles['js'] as $handle => $url) {
        wp_enqueue_script($handle, $url, ['jquery'], MONA_THEME_VERSION, [
            'in_footer' => true,
            'strategy' => 'defer',
        ]);
    }
    wp_enqueue_script('mona-main', MONA_SITE_TEMPLATE_URL . '/assets/js/main.js', ['jquery'], MONA_THEME_VERSION, [
        'in_footer' => true,
        'strategy' => 'defer',
    ]);
}, 2);

/**
 * Translate nav menu items (title & URL) across languages.
 */
add_filter('nav_menu_item_title', function ($title) {
    $lang = function_exists('pll_current_language') ? pll_current_language('slug') : apply_filters('wpml_current_language', null);
    if (empty($lang) && defined('ICL_LANGUAGE_CODE')) {
        $lang = ICL_LANGUAGE_CODE;
    }
    if (empty($lang) || $lang === 'vi') {
        return $title;
    }
    $map = [
        'Giới thiệu' => [
            'en' => 'About Us',
            'lo' => 'ກ່ຽວກັບພວກເຮົາ',
            'ko' => '회사 소개',
            'zh-hant' => '关于我们',
            'zh' => '关于我们',
        ],
        'Lĩnh vực' => [
            'en' => 'Business Sectors',
            'lo' => 'ຂະແໜງການ',
            'ko' => '사업 분야',
            'zh-hant' => '业务领域',
            'zh' => '业务领域',
        ],
        'Dự án' => [
            'en' => 'Projects',
            'lo' => 'ໂຄງການ',
            'ko' => '프로젝트',
            'zh-hant' => '项目',
            'zh' => '项目',
        ],
        'Bền vững' => [
            'en' => 'Sustainability',
            'lo' => 'ຄວາມຍືນຍົງ',
            'ko' => '지속 가능성',
            'zh-hant' => '可持续发展',
            'zh' => '可持续发展',
        ],
        'Tin tức' => [
            'en' => 'News',
            'lo' => 'ຂ່າວສານ',
            'ko' => '뉴스',
            'zh-hant' => '新闻',
            'zh' => '新闻',
        ],
        'Tuyển dụng' => [
            'en' => 'Recruitment',
            'lo' => 'ຮັບສະໝັກ',
            'ko' => '채용',
            'zh-hant' => '招聘',
            'zh' => '招聘',
        ],
        'TUYỂN DỤNG' => [
            'en' => 'RECRUITMENT',
            'lo' => 'ຮັບສະໝັກ',
            'ko' => '채용',
            'zh-hant' => '招聘',
            'zh' => '招聘',
        ],
        'Liên hệ' => [
            'en' => 'Contact',
            'lo' => 'ຕິດຕໍ່',
            'ko' => '연락처',
            'zh-hant' => '联系我们',
            'zh' => '联系我们',
        ],
    ];
    $stripped = trim(wp_strip_all_tags($title));
    if (isset($map[$stripped][$lang])) {
        return $map[$stripped][$lang];
    }
    $lang_short = strtok($lang, '-');
    if (isset($map[$stripped][$lang_short])) {
        return $map[$stripped][$lang_short];
    }
    return $title;
}, 10, 1);

add_filter('nav_menu_link_attributes', function ($atts) {
    if (! empty($atts['href'])) {
        $atts['href'] = mona_localize_url($atts['href']);
    }
    return $atts;
}, 10, 1);

/**
 * Ensure default language filter in WP Admin is Vietnamese (vi)
 * so that admin lists show clean, primary Vietnamese pages/posts.
 * [FIXED] This hook was breaking Polylang's "Show all languages" feature globally.
 */
// add_action('admin_init', function () {
//     $uid = get_current_user_id();
//     if ($uid) {
//         $filter = get_user_meta($uid, 'pll_filter_content', true);
//         if (empty($filter)) {
//             update_user_meta($uid, 'pll_filter_content', 'vi');
//         }
//     }
// });

/**
 * Register theme strings for Polylang translation.
 * This ensures they appear in the Strings Translation table
 * under group "KN Vientiane" (admin → Languages → Translations).
 */
add_action('init', function() {
    if (function_exists('pll_register_string')) {
        $g = 'KN Vientiane';

        // ── Trang Tuyển dụng (archive) ──
        pll_register_string('tuyen_dung_title',         'Tuyển dụng',              $g);
        pll_register_string('tuyen_dung_search',        'Tìm vị trí ứng tuyển...', $g);
        pll_register_string('tuyen_dung_all_positions', 'Tất cả vị trí',           $g);
        pll_register_string('tuyen_dung_all_locations', 'Tất cả địa điểm',         $g);
        pll_register_string('tuyen_dung_reset',         'Đặt lại',                 $g);
        pll_register_string('tuyen_dung_clear',         'Xóa',                     $g);
        pll_register_string('tuyen_dung_no_data',       'Không tìm thấy dữ liệu nào', $g);

        // ── Trang 404 ──
        pll_register_string('404_title',    'Không tìm thấy trang', $g);
        pll_register_string('404_desc',     'Trang bạn đang tìm kiếm không tồn tại hoặc đã được chuyển sang địa chỉ khác.', $g);
        pll_register_string('404_btn_home', 'Trở về trang chủ',     $g);

        // ── Footer / Navigation ──
        pll_register_string('footer_policy',      'Chính sách',  $g);
        pll_register_string('footer_recruitment',  'Tuyển dụng', $g);

        // ── Sidebar / Widget ──
        pll_register_string('widget_categories',  'DANH MỤC',                      $g);
        pll_register_string('widget_recommended', 'Đề xuất bạn đọc',               $g);
        pll_register_string('widget_promo_title', 'RA MẮT DỰ ÁN BẤT ĐỘNG SẢN',    $g);
        pll_register_string('widget_view_now',    'Xem ngay',                       $g);

        // ── Dự án ──
        pll_register_string('project_details',     'Dự án chi tiết',                $g);
        pll_register_string('project_label',       'Dự án',                         $g);
        pll_register_string('project_list_title',  'DANH SÁCH DỰ ÁN',              $g);
        pll_register_string('project_no_featured', 'Chưa có dự án tiêu biểu nào.', $g);

        // ── Chi tiết Tuyển dụng (single) ──
        pll_register_string('recruit_detail_title',   'CHI TIẾT TIN TUYỂN DỤNG',             $g);
        pll_register_string('recruit_quantity',       'Số lượng',                             $g);
        pll_register_string('recruit_level',          'Cấp bậc',                              $g);
        pll_register_string('recruit_experience',     'Kinh nghiệm',                          $g);
        pll_register_string('recruit_experience_label', 'Kinh nghiệm: ',                      $g);
        pll_register_string('recruit_location',       'Địa điểm làm việc',                    $g);
        pll_register_string('recruit_work_type',      'Hình thức làm việc',                   $g);
        pll_register_string('recruit_gender',         'Giới tính',                             $g);
        pll_register_string('recruit_team_photos',    'Hình ảnh đội ngũ',                     $g);
        pll_register_string('recruit_general_info',   'Thông tin chung',                       $g);
        pll_register_string('recruit_apply_now',      'Ứng tuyển ngay',                        $g);
        pll_register_string('recruit_apply',          'Ứng tuyển',                             $g);
        pll_register_string('recruit_other_positions','CÁC VỊ TRÍ KHÁC',                      $g);
        pll_register_string('recruit_no_other',       'Không có vị trí tuyển dụng nào khác',   $g);
        pll_register_string('recruit_expired',        'Hết hạn',                               $g);
        pll_register_string('recruit_hiring',         'Đang tuyển',                            $g);
        pll_register_string('recruit_position_label', 'Vị trí:',                               $g);
        pll_register_string('recruit_location_label', 'Địa điểm:',                             $g);
        pll_register_string('recruit_benefits',       'Phúc lợi công ty',                      $g);

        // ── Tin tức / Bài viết ──
        pll_register_string('post_share',         'Chia sẻ:',          $g);
        pll_register_string('post_featured_info', 'Thông tin nổi bật', $g);
        pll_register_string('post_other_news',    'Tin tức khác',      $g);
        pll_register_string('post_view_detail',   'Xem chi tiết',      $g);

        // ── Chung ──
        pll_register_string('common_content_soon',   'Nội dung sẽ sớm được cập nhật',             $g);
        pll_register_string('common_contact_info',   'Thông tin',                                   $g);
        pll_register_string('common_contact',        'LIÊN HỆ',                                    $g);
        pll_register_string('common_apply_info',     'ỨNG TUYỂN',                                  $g);
        pll_register_string('common_cooperate',      'Hợp tác cùng chúng tôi',                     $g);
        pll_register_string('common_no_field_posts', 'Chưa có bài viết nào trong lĩnh vực này.',   $g);
        pll_register_string('common_no_service',     'Chưa có dịch vụ tiện ích nào.',              $g);
    }
});

/**
 * Lock Polylang language dropdown on existing posts (post.php)
 * to prevent accidental language changes that break translation links.
 */
add_action('admin_head', function () {
    global $pagenow;
    if ($pagenow === 'post.php' || $pagenow === 'term.php') {
        ?>
        <style>
            #ml_box select.post_lang_choice,
            .post_lang_choice,
            #term_lang_choice,
            select[name="term_lang_choice"] {
                pointer-events: none;
                background-color: #f0f0f1 !important;
                color: #50575e !important;
                border-color: #dcdcde !important;
                cursor: not-allowed;
            }
            .pll-lang-choice-locked-notice {
                display: block;
                margin-top: 5px;
                font-size: 11px;
                color: #0073aa;
                font-style: italic;
            }
        </style>
        <script>
            jQuery(function($) {
                $('.post_lang_choice, #term_lang_choice, select[name="term_lang_choice"]').attr('tabindex', '-1');
                if (!$('.pll-lang-choice-locked-notice').length) {
                    $('.post_lang_choice, #term_lang_choice, select[name="term_lang_choice"]').after('<div class="pll-lang-choice-locked-notice">🔒 Ngôn ngữ đã cố định để bảo vệ liên kết dịch</div>');
                }
            });
        </script>
        <?php
    }
});

/**
 * Move Yoast SEO metabox to the very bottom across all post types in WP Admin.
 */
add_filter('wpseo_metabox_prio', function () {
    return 'low';
});

add_action('admin_footer', function () {
    global $pagenow;
    if (in_array($pagenow, ['post.php', 'post-new.php'], true)) {
        ?>
        <script>
            jQuery(function($) {
                var $wpseo = $('#wpseo_meta');
                if ($wpseo.length) {
                    // Move to the very bottom of the main content column
                    $('#normal-sortables').append($wpseo);
                }
            });
        </script>
        <?php
    }
});


