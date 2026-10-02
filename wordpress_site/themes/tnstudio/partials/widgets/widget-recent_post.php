<?php
defined('ABSPATH') || exit;

if (empty($args['data']))
    return;

$data = $args['data'];

$options = [
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 6,
    'no_found_rows' => true,
    // 'meta_query' => ['relation' => 'AND'],
    // 'tax_query' => ['relation' => 'AND'],
];

if (! empty($data['list']) && is_array($data['list'])) {
    $raw_ids = array_filter(array_map('intval', $data['list']));
    $cur_lang = function_exists('mona_ui_lang') ? mona_ui_lang() : 'vi';
    $post_ids = [];
    foreach ($raw_ids as $p_id) {
        $tr_id = ($cur_lang !== 'vi' && function_exists('mona_get_translated_post_id')) ? mona_get_translated_post_id($p_id, $cur_lang) : $p_id;
        $post_ids[] = $tr_id ?: $p_id;
    }
    if (! empty($post_ids)) {
        $options['posts_per_page'] = count($post_ids);
        $options['post__in'] = $post_ids;
        $options['orderby'] = 'post__in';
    }
} elseif (! empty($data['posts']) && is_array($data['posts'])) {
    $options['posts_per_page'] = count($data['posts']);
    $options['post__in'] = $data['posts'];
    $options['orderby'] = 'post__in';
}

$custom_query = new WP_Query($options);
if ($custom_query->have_posts()) {
?>
    <?php
    // Translate widget title based on WPML current language.
    $mona_wr_lang = apply_filters('wpml_current_language', null);
    if (empty($mona_wr_lang) && defined('ICL_LANGUAGE_CODE')) {
      $mona_wr_lang = ICL_LANGUAGE_CODE;
    }
    if (empty($mona_wr_lang)) {
      $mona_wr_lang = 'vi';
    }
    $mona_wr_title = $data['title'] ?? '';
    if (function_exists('pll__') && $mona_wr_title !== '') {
      $mona_wr_title = pll__($mona_wr_title);
    }
    ?>
    <div class="cate-box transp-bg">
        <!-- <div class="btn-clost-mb">
            <img src="/template/assets/images/icons/close-btn.svg" alt="" title="" loading="lazy">
        </div> -->
        <?php if (! empty($data['title'])) : ?>
            <div class="text-20"><?php echo esc_html($mona_wr_title); ?></div>
        <?php endif; ?>
        <div class="cate-post-slide js-cate-slide">
            <div class="swiper">
                <div class="swiper-wrapper">
                    <?php while ($custom_query->have_posts()) : $custom_query->the_post(); ?>
                        <div class="swiper-slide">
                            <?php get_template_part('partials/components/loops/item', 'post'); ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
<?php
}
wp_reset_postdata();
?>