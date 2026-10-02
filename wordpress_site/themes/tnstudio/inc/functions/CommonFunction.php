<?php

/**
 * Check if current page is the homepage in any language.
 *
 * WordPress's is_front_page() only works for the page set as "page_on_front".
 * Polylang translated homepage IDs (1683, 1685, 1486, 1684) are NOT
 * recognized by is_front_page() — so body class, fullPage.js, CSS all break.
 *
 * This helper checks the queried object ID against all known homepage IDs.
 */
if (!function_exists('mona_is_homepage')) {
    function mona_is_homepage(): bool
    {
        if (is_front_page()) {
            return true;
        }

        static $home_ids = null;
        if ($home_ids === null) {
            $front_id = (int) get_option('page_on_front');
            $home_ids = $front_id ? [$front_id] : [14];

            if (function_exists('pll_get_post_translations') && $front_id) {
                $tr = pll_get_post_translations($front_id);
                if (is_array($tr)) {
                    $home_ids = array_unique(array_merge($home_ids, array_map('intval', array_values($tr))));
                }
            } else {
                $home_ids = [14, 1683, 1685, 1486, 1684];
            }
        }

        return in_array((int) get_queried_object_id(), $home_ids, true);
    }
}

/**
 * Convert text tel to link
 * 
 * @param string $hotline
 * 
 * @return string
 */
if (!function_exists('mona_replace_tel')) {
    function mona_replace_tel($hotline = '')
    {
        if (empty($hotline)) {
            return;
        }
        $string = preg_replace('/\s+/', '', $hotline);
        $stringaz = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $string);
        $tel = 'tel:' . $stringaz;
        return $tel;
    }
}


/**
 * Debug variable
 */
if (!function_exists('mona_debug')) {
    function mona_debug(...$args)
    {
        echo '<pre>';
        var_dump($args);
        echo '</pre>';
    }
}


/**
 * Remove p tag
 * 
 * @param string $content
 * @return string
 */
if (!function_exists('mona_remove_p_tag')) {
    function mona_remove_p_tag(string $content): string
    {
        return preg_replace('/<p>(.*)<\/p>/', '\1', $content);
    }
}


/**
 * Get list link for breadcrumb
 */
if (!function_exists('mona_get_list_breadcrumb')) {
    function mona_get_list_breadcrumb()
    {
        global $wp_rewrite, $post;

        $rewriteUrl = $wp_rewrite->using_permalinks();

        // Function create item link
        $create_item_link = function (string $title, string $permalink, bool $is_active = false) {
            return array(
                'title' => $title,
                'url' => $permalink,
                'is-active' => $is_active,
            );
        };

        // Result
        $result = array();

        // Default title pages
        $ar_title = array(
            'home' => __('Trang chủ', 'monamedia'),
            'search' => __('Kết quả tìm kiếm ', 'monamedia'),
            '404' => __('Lỗi 404', 'monamedia'),
            'tagged' => __('Được gán thẻ ', 'monamedia'),
            'author' => __('Các bài viết được đăng bởi ', 'monamedia'),
            'page' => __('Trang', 'monamedia'),
        );

        // If not front page
        if (!is_front_page() || is_paged()) {
            // Home
            $homeLink = esc_url(home_url('/'));
            $result[] = $create_item_link($ar_title['home'], $homeLink);

            // Category page
            if (is_category()) {
                // Page blog
                if (MONA_PAGE_BLOG) {
                    $result[] = $create_item_link(
                        get_post_field('post_title', MONA_PAGE_BLOG),
                        get_permalink(MONA_PAGE_BLOG)
                    );
                }

                // Cats
                $current_cat = get_queried_object();

                // Parent
                if ($current_cat->parent != 0) {
                    $parent_cat = get_category($current_cat->parent);

                    $result[] = $create_item_link($parent_cat->name, get_category_link($parent_cat->term_id));
                }

                $result[] = $create_item_link($current_cat->name, '#', true);
            }
            // Tag page
            elseif (is_tag()) {
                // Page blog
                if (MONA_PAGE_BLOG) {
                    $result[] = $create_item_link(
                        get_post_field('post_title', MONA_PAGE_BLOG),
                        get_permalink(MONA_PAGE_BLOG)
                    );
                }

                $result[] = $create_item_link(single_tag_title('', false), '#', true);
            }
            // Taxonomy page
            elseif (is_tax()) {
                $term = get_queried_object();

                // Parent
                if ($term->parent != 0) {
                    $parent = get_term($term->parent, $term->taxonomy);

                    $result[] = $create_item_link($parent->name, get_term_link($parent->term_id));
                }

                $result[] = $create_item_link(single_tag_title('', false), '#', true);
            } elseif (is_post_type_archive('tuyen-dung')) {
                $obj = get_post_type_object('tuyen-dung');

                $result[] = $create_item_link(esc_html(__($obj->labels->name, 'monamedia')), '#', true);
            }
            // Blog page
            elseif (is_home()) {
                $result[] = $create_item_link(get_the_title(MONA_PAGE_BLOG), '#', true);
            }
            // Search page
            elseif (is_search()) {
                $result[] = $create_item_link($ar_title['search'] . '"' . get_search_query() . '"', '#', true);
            }
            // Day archive
            elseif (is_day()) {
                $result[] = $create_item_link(get_the_time('d'), '#', true);
            }
            // Month archive
            elseif (is_month()) {
                $result[] = $create_item_link(get_the_time('F'), '#', true);
            }
            // Year archive
            elseif (is_year()) {
                $result[] = $create_item_link(get_the_time('Y'), '#', true);
            }
            // Single
            elseif (is_single() && !is_attachment()) {
                switch ($post->post_type) {
                    case 'post':
                        // Page blog
                        if (MONA_PAGE_BLOG) {
                            $result[] = $create_item_link(
                                get_post_field('post_title', MONA_PAGE_BLOG),
                                get_permalink(MONA_PAGE_BLOG)
                            );
                        }

                        // Category
                        $cat = mona_get_primary_term($post->ID);
                        if ($cat && !is_wp_error($cat)) {
                            $result[] = $create_item_link($cat->name, get_category_link($cat->term_id));
                        }

                        // Current post
                        $result[] = $create_item_link(get_the_title(), '#', true);
                        break;

                    case 'tuyen-dung':
                        $obj = get_post_type_object('tuyen-dung');
                        $result[] = $create_item_link(
                            esc_html(__($obj->labels->name, 'monamedia')),
                            get_post_type_archive_link('tuyen-dung')
                        );

                        $result[] = $create_item_link(get_the_title(), '#', true);
                        break;

                    case 'du-an':
                        $obj = get_post_type_object('du-an');

                        $project_page = get_page_by_path('du-an');
                        $project_page_id = $project_page ? (function_exists('pll_get_post') ? (pll_get_post($project_page->ID) ?: $project_page->ID) : apply_filters('wpml_object_id', $project_page->ID, 'page', true)) : 0;
                        $project_title = $project_page_id ? get_the_title($project_page_id) : esc_html(__($obj->labels->name, 'monamedia'));

                        $result[] = $create_item_link(
                            $project_title,
                            $project_page_id ? get_permalink($project_page_id) : '#'
                        );

                        $result[] = $create_item_link(get_the_title(), '#', true);
                        break;

                    case 'linh-vuc-hoat-dong':
                        $sector_page_id = function_exists('pll_get_post') ? (pll_get_post(307) ?: 307) : (apply_filters('wpml_object_id', 307, 'page', true) ?: 307);
                        $sector_title = get_the_title($sector_page_id);

                        $result[] = $create_item_link(
                            $sector_title,
                            get_permalink($sector_page_id)
                        );

                        $result[] = $create_item_link(get_the_title(), '#', true);
                        break;

                    default:
                        $url = "#";

                        $post_type = get_post_type_object($post->post_type);

                        $post_type_name = $post_type->labels->singular_name;

                        // Detech page template case
                        if (!empty($post_type->rewrite['slug'])) {

                            $pages = get_pages(
                                array(
                                    'post_type' => 'page',
                                    'meta_key' => '_wp_page_template',
                                    'meta_value' => 'page-template/' . trim($post_type->rewrite['slug']) . '-template.php'
                                )
                            );

                            if (isset($pages[0])) {
                                $array = (array) $pages[0];

                                $url = get_page_link($array['ID']);
                            }
                        }

                        if ($rewriteUrl) {
                            $result[] = $create_item_link($post_type_name, $url);
                        } else {
                            $result[] = $create_item_link($post_type_name, $homeLink . '?post_type=' . $post->post_type);
                        }

                        $result[] = $create_item_link(get_the_title(), '#', true);
                        break;
                }
            }
            // Attachment
            elseif (is_attachment()) {
                if ($post->post_parent != 0) {
                    $parent = get_post($post->post_parent);
                    $cat = get_the_category($parent->ID);

                    // Category
                    if (!empty($cat)) {
                        $cat = $cat[0];
                        $result[] = $create_item_link($cat->name, get_category_link($cat->term_id));
                    }

                    $result[] = $create_item_link($parent->post_title, get_permalink($parent));
                }

                $result[] = $create_item_link(get_the_title(), '#', true);
            }
            // Page not have parent
            elseif (is_page() && !$post->post_parent) {
                $result[] = $create_item_link(get_the_title(), '#', true);
            }
            // Page have parent
            elseif (is_page() && $post->post_parent) {
                $parent_id = $post->post_parent;
                $breadcrumbs = array();

                while ($parent_id > 0) {
                    $page = get_post($parent_id);

                    $breadcrumbs[] = $create_item_link(get_the_title($page->ID), get_permalink($page->ID));

                    $parent_id = $page->post_parent;
                }

                // Reverse sibling
                $breadcrumbs = array_reverse($breadcrumbs);

                $breadcrumbs[] = $create_item_link(get_the_title(), '#', true);

                $result = array_merge($result, $breadcrumbs);
            }
            // Author page
            elseif (is_author()) {
                global $author;
                $userdata = get_userdata($author);

                $result[] = $create_item_link($ar_title['author'] . $userdata->display_name, '#', true);
            }
            // 404 page
            elseif (is_404()) {
                $result[] = $create_item_link($ar_title['404'], '#', true);
            } elseif (is_post_type_archive('tuyen-dung')) {
                $obj = get_post_type_object('tuyen-dung');

                $result[] = $create_item_link(esc_html(__($obj->labels->name, 'monamedia')), '#', true);
            } else {
                $result[] = $create_item_link(get_the_title(), '#', true);
            }
        }

        return $result;
    }
}


/**
 * Ouput breadcrumb
 */
if (!function_exists('mona_output_breadcrumb')) {
    function mona_output_breadcrumb()
    {
        $links = mona_get_list_breadcrumb();

        if (empty($links))
            return;

        get_template_part('partials/components/breadcrumb', null, array(
            'links' => $links,
        ));
    }
}

/**
 * True if a post belongs to the current WPML language (or WPML inactive).
 * Used to guard secondary WP_Query loops that WPML does not reliably filter.
 */
function mona_post_in_lang($post_id): bool
{
    if (function_exists('pll_get_post_language') && function_exists('pll_current_language')) {
        $cur = pll_current_language('slug');
        if (empty($cur))
            return true;
        $post_lang = pll_get_post_language($post_id, 'slug');
        return empty($post_lang) || $post_lang === $cur;
    }
    $cur = apply_filters('wpml_current_language', null);
    if (empty($cur))
        return true;
    $pl = apply_filters('wpml_post_language_details', null, $post_id);
    return !(is_array($pl) && !empty($pl['language_code']) && $pl['language_code'] !== $cur);
}

function mona_render_section(int|WP_Post $id, array $args = []): void
{
    /**
     * Đổi sang bản dịch của bài web-builder theo ngôn ngữ đang xem.
     *
     * Các field trỏ tới web-builder lưu ID của bài GỐC (tiếng Việt). Không ánh
     * xạ thì trang ngôn ngữ khác vẫn nạp đúng bài tiếng Việt đó — ngoài trang
     * tiếng Trung thấy nguyên khối "HOẠT ĐỘNG", "LIÊN HỆ", "KM 17, đường Tha
     * Deua..." bằng tiếng Việt dù nội dung trang đã dịch.
     *
     * footer.php đã làm đúng cách này từ trước; đưa lên đây để mọi nơi gọi
     * mona_render_section() đều được, khỏi vá từng chỗ.
     *
     * Tham số cuối là true = chưa có bản dịch thì trả lại ID gốc, nên ngôn ngữ
     * nào chưa dịch vẫn hiển thị y như cũ.
     */
    $id = mona_web_builder_id($id instanceof WP_Post ? $id->ID : $id);

    $layout = get_field('layout', $id);
    if (empty($layout))
        return;

    $default_args = ['object_id' => $id];
    if (!empty($args)) {
        $default_args = wp_parse_args($args, $default_args);
    }

    get_template_part(
        'partials/sections/section',
        str_replace('section_', '', $layout),
        $default_args
    );
}

function mona_get_current_url(): string
{
    global $wp;
    return home_url($wp->request);
}

function mona_is_current_url(string $url): bool
{
    $current_url = mona_get_current_url();

    return untrailingslashit($current_url) === untrailingslashit($url);
}

if (!function_exists('mona_get_youtube_id')) {
    /**
     * Tách ID video từ link YouTube (watch / youtu.be / embed / shorts),
     * hoặc trả lại chính chuỗi nếu người nhập dán thẳng ID.
     */
    function mona_get_youtube_id(string $url): string
    {
        $url = trim($url);

        if ($url === '') {
            return '';
        }

        $patterns = [
            '#youtu\.be/([A-Za-z0-9_-]{11})#',
            '#youtube\.com/watch\?(?:.*&)?v=([A-Za-z0-9_-]{11})#',
            '#youtube\.com/embed/([A-Za-z0-9_-]{11})#',
            '#youtube\.com/shorts/([A-Za-z0-9_-]{11})#',
            '#youtube\.com/v/([A-Za-z0-9_-]{11})#',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        if (preg_match('#^[A-Za-z0-9_-]{11}$#', $url)) {
            return $url;
        }

        return '';
    }
}

if (!function_exists('mona_get_acf_file_url')) {
    /**
     * Lấy URL file từ field ACF bất kể field đó trả về gì:
     * chuỗi URL, ID attachment, hay mảng attachment.
     */
    function mona_get_acf_file_url($value): string
    {
        if (empty($value)) {
            return '';
        }

        if (is_array($value)) {
            return $value['url'] ?? '';
        }

        if (is_numeric($value)) {
            return (string) wp_get_attachment_url((int) $value);
        }

        return is_string($value) ? $value : '';
    }
}

if (!function_exists('mona_render_hero_media')) {
    /**
     * Render nền cho khối hero: ảnh, video upload, hoặc YouTube nhúng.
     *
     * Dựa vào field `banner_type` trong group ACF (image | video | youtube).
     * Ảnh luôn được dùng làm poster của video để không bị chớp trắng lúc tải,
     * và làm fallback khi thiếu link.
     *
     * @param array $data      Mảng field của section (section_hero, section_banner...).
     * @param array $field_map Đổi tên key nếu section dùng tên field khác.
     */
    function mona_render_hero_media(array $data, array $field_map = []): void
    {
        $keys = wp_parse_args($field_map, [
            'type' => 'banner_type',
            'image' => 'images',
            'video' => 'video_url',
            'youtube' => 'youtube_url',
        ]);

        $type = $data[$keys['type']] ?? 'image';
        $image_id = $data[$keys['image']] ?? 0;
        $poster_url = $image_id ? (string) wp_get_attachment_image_url($image_id, 'full') : '';

        if ($type === 'video') {
            $video_url = mona_get_acf_file_url($data[$keys['video']] ?? '');

            if ($video_url) {
                ?>
                <video class="mona-hero-video" data-autoplay autoplay muted loop playsinline preload="auto" <?php echo $poster_url ? 'poster="' . esc_url($poster_url) . '"' : ''; ?>>
                    <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                </video>
                <?php
                return;
            }
        }

        if ($type === 'youtube') {
            $youtube_id = mona_get_youtube_id((string) ($data[$keys['youtube']] ?? ''));

            if ($youtube_id) {
                // Nền trang trí: tắt điều khiển, chặn tương tác, bắt buộc mute để
                // trình duyệt cho phép autoplay. playlist=<id> là cách duy nhất
                // để loop=1 hoạt động với video đơn lẻ.
                $embed_url = add_query_arg(
                    [
                        'autoplay' => 1,
                        'mute' => 1,
                        'loop' => 1,
                        'playlist' => $youtube_id,
                        'controls' => 0,
                        'showinfo' => 0,
                        'rel' => 0,
                        'modestbranding' => 1,
                        'playsinline' => 1,
                        'disablekb' => 1,
                        'iv_load_policy' => 3,
                    ],
                    'https://www.youtube.com/embed/' . $youtube_id
                );
                ?>
                <div class="mona-hero-yt" aria-hidden="true">
                    <iframe src="<?php echo esc_url($embed_url); ?>" title="" frameborder="0" tabindex="-1"
                        allow="autoplay; encrypted-media; picture-in-picture"></iframe>
                </div>
                <?php
                return;
            }
        }

        // Mặc định (type = image) và fallback khi thiếu link video/youtube
        if ($image_id) {
            echo wp_get_attachment_image($image_id, 'full', false, ['loading' => 'lazy']);
        }
    }
}

if (!function_exists('mona_link_attrs')) {
    /**
     * Sinh chuỗi thuộc tính cho thẻ <a> từ field ACF kiểu Link.
     *
     * Trả về chuỗi rỗng khi không có link — bên gọi dựa vào đó để render <div>
     * thay vì <a>, tránh tạo ra thẻ link rỗng không bấm được.
     *
     * @param mixed $link Mảng ACF Link, hoặc chuỗi URL.
     */
    function mona_link_attrs($link): string
    {
        if (is_array($link)) {
            $url = $link['url'] ?? '';
            $target = $link['target'] ?? '';
        } else {
            $url = is_string($link) ? $link : '';
            $target = '';
        }

        $url = trim((string) $url);

        if ($url === '') {
            return '';
        }

        $attrs = 'href="' . esc_url($url) . '"';

        if ($target) {
            $attrs .= ' target="' . esc_attr($target) . '" rel="noopener"';
        }

        return $attrs;
    }
}

if (!function_exists('mona_render_creative_value_section')) {
    /**
     * Render section "Kiến tạo giá trị".
     *
     * Ưu tiên nhóm field khai báo thẳng trên trang (section_slogan). Nếu nút
     * "Dùng cấu hình này" chưa bật thì rơi về đường cũ qua Web builder, nên
     * các trang chưa chuyển đổi vẫn chạy nguyên như trước.
     *
     * @param mixed $web_builder_id Giá trị field section_creative_value (ID post Web builder).
     * @param int   $object_id      ID trang/bài đang hiển thị.
     */
    function mona_render_creative_value_section($web_builder_id, int $object_id): void
    {
        $custom = get_field('section_slogan', $object_id);

        if (!empty($custom['show'])) {
            get_template_part('partials/sections/section', 'slogan-custom', ['data' => $custom]);
            return;
        }

        if (!empty($web_builder_id)) {
            mona_render_section($web_builder_id);
        }
    }
}

if (!function_exists('mona_get_translated_post_id')) {
    /**
     * Lấy ID bài viết dịch tương ứng cho một ngôn ngữ cụ thể.
     * Hỗ trợ fallback qua icl_translations table, post_translations taxonomy, Polylang và WPML.
     */
    function mona_get_translated_post_id(int $post_id, string $code): int
    {
        if (empty($post_id) || empty($code)) {
            return 0;
        }

        // 1. Direct DB lookup via icl_translations table (most reliable across all CPTs)
        global $wpdb;
        $trid = $wpdb->get_var($wpdb->prepare(
            "SELECT trid FROM {$wpdb->prefix}icl_translations WHERE element_id = %d LIMIT 1",
            $post_id
        ));
        if ($trid) {
            $found = $wpdb->get_var($wpdb->prepare(
                "SELECT element_id FROM {$wpdb->prefix}icl_translations WHERE trid = %d AND language_code = %s LIMIT 1",
                $trid,
                $code
            ));
            if ($found) {
                return (int) $found;
            }
        }

        // 2. Polylang post_translations taxonomy description fallback
        $terms = wp_get_object_terms($post_id, 'post_translations');
        if (!empty($terms) && !is_wp_error($terms)) {
            $desc = maybe_unserialize($terms[0]->description);
            if (is_array($desc) && !empty($desc[$code])) {
                return (int) $desc[$code];
            }
        }

        // 3. Polylang API
        if (function_exists('pll_get_post')) {
            $tr_id = pll_get_post($post_id, $code);
            if ($tr_id) {
                return (int) $tr_id;
            }
        }

        // 4. WPML Filter
        $pt = get_post_type($post_id);
        $tr_id = apply_filters('wpml_object_id', $post_id, $pt, false, $code);
        if ($tr_id) {
            return (int) $tr_id;
        }

        return 0;
    }
}

if (!function_exists('mona_get_languages')) {
    /**
     * Lấy danh sách ngôn ngữ kèm URL đã dịch tương ứng cho trang hiện tại.
     *
     * @return array
     */
    function mona_get_languages(): array
    {
        $languages = [];
        $cur_lang = function_exists('pll_current_language') ? pll_current_language('slug') : apply_filters('wpml_current_language', null);

        // Detect homepage from URL path
        $req_path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
        $is_front = in_array($req_path, [
            '',
            'en',
            'lo',
            'zh-hant',
            'ko',
            'zh',
            'trang-chu',
            'en/trang-chu',
            'lo/trang-chu',
            'zh-hant/trang-chu',
            'ko/trang-chu',
            'zh-hant/home',
            'en/home',
            'lo/home',
            'ko/home'
        ], true);

        $queried_id = (int) get_queried_object_id();

        if (function_exists('pll_languages_list')) {
            $all_langs = pll_languages_list(['fields' => '']);
            if (!empty($all_langs) && is_array($all_langs)) {
                $base = untrailingslashit(get_option('home'));
                $raw = function_exists('pll_the_languages') ? pll_the_languages(['raw' => 1]) : [];

                foreach ($all_langs as $lang_obj) {
                    $code = $lang_obj->slug;
                    $url = '';

                    if ($is_front) {
                        $url = ($code === 'vi') ? $base . '/' : $base . '/' . $code . '/';
                    } elseif ($queried_id && is_singular()) {
                        $tr_id = mona_get_translated_post_id($queried_id, $code);
                        if ($tr_id) {
                            clean_object_term_cache($tr_id, get_post_type($tr_id));
                            $url = get_permalink($tr_id);
                        }
                    }

                    if (empty($url) && !empty($raw[$code]['url']) && empty($raw[$code]['no_translation'])) {
                        $url = $raw[$code]['url'];
                    }

                    if (empty($url)) {
                        $url = ($code === 'vi') ? $base . '/' : $base . '/' . $code . '/';
                    }

                    $languages[$code] = [
                        'language_code' => $code,
                        'code' => $code,
                        'native_name' => $lang_obj->name,
                        'url' => $url,
                        'active' => ($code === $cur_lang) ? 1 : 0,
                    ];
                }
            }
        }

        if (empty($languages)) {
            $languages = apply_filters('wpml_active_languages', null, ['skip_missing' => 0]);
        }

        if (empty($languages) || !is_array($languages)) {
            return [];
        }

        $order = ['vi' => 0, 'lo' => 1, 'en' => 2, 'zh-hant' => 3, 'zh-hans' => 3, 'zh' => 3, 'ko' => 4];

        // Kèm chỉ số gốc để hai ngôn ngữ cùng hạng vẫn giữ nguyên thứ tự ban đầu
        $indexed = [];
        foreach (array_values($languages) as $index => $language) {
            $code = $language['language_code'] ?? '';
            $indexed[] = [$order[$code] ?? 100, $index, $language];
        }

        usort($indexed, static function ($a, $b) {
            return $a[0] === $b[0] ? $a[1] <=> $b[1] : $a[0] <=> $b[0];
        });

        return array_column($indexed, 2);
    }
}

if (!function_exists('mona_lang_label')) {
    /**
     * Nhãn ngắn cho bộ chọn ngôn ngữ ở header.
     *
     * Mặc định lấy mã ngôn ngữ viết hoa, nhưng mã có vùng như `zh-hant` sẽ ra
     * "ZH-HANT" — dài gấp đôi các mục còn lại và tràn khung dropdown (khách đã
     * chụp màn hình báo lỗi này). Bảng dưới chốt nhãn cho từng ngôn ngữ đang có.
     */
    function mona_lang_label(string $code): string
    {
        $map = [
            'vi' => 'VI',
            'lo' => 'LA',
            'en' => 'EN',
            'zh-hant' => 'ZH',
            'zh-hans' => 'ZH',
            'zh' => 'ZH',
            'ko' => 'KO',
        ];

        return $map[$code] ?? strtoupper(strtok($code, '-'));
    }
}

if (!function_exists('mona_lang_flag_url')) {
    /**
     * Cờ theo mã ngôn ngữ. Mã có vùng (zh-hant) thì lùi về mã gốc (zh).
     */
    function mona_lang_flag_url(string $code): string
    {
        foreach ([$code, strtok($code, '-')] as $try) {
            if ($try && file_exists(MONA_THEME_PATH . '/assets/images/flags/' . $try . '.svg')) {
                return MONA_THEME_PATH_URI . '/assets/images/flags/' . $try . '.svg';
            }
        }

        return MONA_SITE_TEMPLATE_URL . '/assets/images/header/lang-img.png';
    }
}

if (!function_exists('mona_ui_lang')) {
    /**
     * Mã ngôn ngữ dùng để tra các bảng dịch chữ cứng nằm rải rác trong theme.
     *
     * Khác với apply_filters('wpml_current_language') ở hai điểm:
     *  - Cắt bỏ phần vùng: WPML đăng ký tiếng Trung là `zh-hant`, trong khi mọi
     *    bảng dịch trong theme đều đặt khóa `zh` — không cắt thì tiếng Trung
     *    luôn rơi về chữ tiếng Việt.
     *  - Luôn trả về một giá trị (mặc định `vi`) để nơi gọi khỏi lặp lại đoạn
     *    kiểm tra ICL_LANGUAGE_CODE.
     */
    function mona_ui_lang(): string
    {
        $lang = function_exists('pll_current_language') ? pll_current_language('slug') : apply_filters('wpml_current_language', null);

        if (empty($lang) && defined('ICL_LANGUAGE_CODE')) {
            $lang = ICL_LANGUAGE_CODE;
        }

        if (empty($lang)) {
            return 'vi';
        }

        return strtok($lang, '-');
    }
}

if (!function_exists('mona_web_builder_id')) {
    /**
     * Đổi ID bài web-builder sang bản dịch của ngôn ngữ đang xem.
     *
     * Các field trỏ tới web-builder lưu ID của bài GỐC (tiếng Việt). Không ánh
     * xạ thì trang ngôn ngữ khác vẫn nạp đúng bài tiếng Việt đó.
     *
     * Thứ tự tra:
     *  1. Liên kết dịch của Polylang / WPML — đúng chuẩn, tự động, ưu tiên dùng.
     *  2. Bảng khai báo trong code (inc/functions/WebBuilderLangMap.php).
     *
     * Không khớp bước nào thì trả lại ID gốc, nên ngôn ngữ chưa dịch vẫn hiển
     * thị y như cũ.
     */
    function mona_web_builder_id($id)
    {
        if (empty($id)) {
            return $id;
        }

        $id = (int) $id;

        // 1. Polylang
        if (function_exists('pll_get_post')) {
            $translated = (int) pll_get_post($id);
            if ($translated) {
                return $translated;
            }
        }

        // 2. WPML — tham số cuối true = chưa có bản dịch thì trả lại chính nó
        $translated = (int) apply_filters('wpml_object_id', $id, 'web-builder', true);
        if ($translated && $translated !== $id) {
            return $translated;
        }

        // 3. Bảng khai báo trong code
        if (!function_exists('mona_web_builder_lang_map')) {
            return $id;
        }

        $lang = function_exists('pll_current_language') ? pll_current_language('slug') : apply_filters('wpml_current_language', null);
        if (empty($lang)) {
            return $id;
        }

        $map = mona_web_builder_lang_map();
        $mapped = $map[$id][$lang] ?? null;

        if ($mapped && get_post_status($mapped) === 'publish') {
            return (int) $mapped;
        }

        return $id;
    }
}
