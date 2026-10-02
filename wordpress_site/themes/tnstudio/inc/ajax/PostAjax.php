<?php
defined('ABSPATH') || exit;

if (! function_exists('mona_ajax_get_posts')) {

    function mona_ajax_get_posts() {

        try {

            if (! check_ajax_referer('mona-ajax-security', 'security', false)) {
                throw new Exception(__('Hành động không được xác thực', 'monamedia'));
            }

            // Run the query in the language the request came from (WPML).
            if (! empty($_POST['lang'])) {
                do_action('wpml_switch_language', sanitize_key($_POST['lang']));
            }

            $posts_per_page = filter_input(INPUT_POST, 'posts_per_page', FILTER_VALIDATE_INT);

            if (! $posts_per_page) {
                $posts_per_page = MONA_POSTS_PER_PAGE;
            }

            $posts_per_page = max(1, min(50, absint($posts_per_page)));

            $paged = filter_input(INPUT_POST, 'paged', FILTER_VALIDATE_INT);

            if (! $paged) {
                $paged = 1;
            }

            $paged = max(1, absint($paged));

            $args = [
                'post_type'           => 'post',
                'post_status'         => 'publish',
                'posts_per_page'      => $posts_per_page,
                'paged'               => $paged,
                'ignore_sticky_posts' => true,
                'orderby'             => [
                    'date' => 'DESC',
                ],
            ];

            /**
             * taxonomies
             * [
             *   category: [1,2]
             * ]
             */

            if (! empty($_POST['taxonomies']) && is_array($_POST['taxonomies'])) {

                $tax_query = [
                    'relation' => 'AND',
                ];

                foreach ($_POST['taxonomies'] as $taxonomy => $terms) {

                    if (! is_array($terms)) {
                        continue;
                    }

                    $taxonomy = sanitize_key($taxonomy);

                    if (! taxonomy_exists($taxonomy)) {
                        continue;
                    }

                    $term_ids = array_values(
                        array_filter(
                            array_map('absint', $terms)
                        )
                    );

                    if (empty($term_ids)) {
                        continue;
                    }

                    $tax_query[] = [
                        'taxonomy' => $taxonomy,
                        'field'    => 'term_id',
                        'terms'    => $term_ids,
                    ];
                }

                if (count($tax_query) > 1) {
                    $args['tax_query'] = $tax_query;
                }
            }

            $query = new WP_Query($args);

            ob_start();

            if ($query->have_posts()) :

                while ($query->have_posts()) :
                    $query->the_post();

            ?>
                    <div class="news-box">
                        <?php
                        get_template_part(
                            'partials/components/loops/item',
                            'post'
                        );
                        ?>
                    </div>
            <?php

                endwhile;

            else :

            ?>
                <p class="mona-empty">
                    <?php echo esc_html(function_exists('pll__') ? pll__('Không có bài viết nào được tìm thấy') : 'Không có bài viết nào được tìm thấy'); ?>
                </p>
            <?php

            endif;

            $posts_html = ob_get_clean();

            wp_reset_postdata();

            wp_send_json([
                'success' => true,
                'data'    => [
                    'posts_html'      => $posts_html,
                    'pagination_html' => mona_pagination_links_ajax($query, $paged),
                    'found_posts'     => $query->found_posts,
                    'max_num_pages'   => $query->max_num_pages,
                    'current_page'    => $paged,
                ],
            ]);

        } catch (\Throwable $th) {

            wp_send_json([
                'success' => false,
                'errors'  => [
                    __('Đã xảy ra sự cố', 'monamedia'),
                    // $th->getMessage(),
                ],
            ]);
        }

        wp_die();
    }
}

add_action('wp_ajax_mona_ajax_get_posts', 'mona_ajax_get_posts');
add_action('wp_ajax_nopriv_mona_ajax_get_posts', 'mona_ajax_get_posts');