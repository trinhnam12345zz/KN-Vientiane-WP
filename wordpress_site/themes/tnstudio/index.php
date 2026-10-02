<?php

/**
 * The template for News archive (Tin tức)
 * 
 * @author TN Studio Lab / Website
 */

if (!defined('ABSPATH')) {
  die();
}

$current_page_id = function_exists('pll_get_post') ? (pll_get_post(MONA_PAGE_BLOG) ?: MONA_PAGE_BLOG) : apply_filters('wpml_object_id', MONA_PAGE_BLOG, 'page', true);

$acf_fields = get_fields($current_page_id);
$featured_posts = $acf_fields['featured_posts'] ?? [];

/**
 * Query cho slide đầu trang.
 * Mặc định lấy 5 bài mới nhất hoặc theo danh sách bài nổi bật được chọn trong admin.
 */
$slide_args = [
  'post_type'      => 'post',
  'post_status'    => 'publish',
  'posts_per_page' => 5,
  'no_found_rows'  => true,
];

$featured_ids = array_filter(array_map(
  static fn($item) => is_object($item) ? (int) $item->ID : (int) $item,
  (array) $featured_posts
));

if ($featured_ids) {
  $slide_args['post__in']       = $featured_ids;
  $slide_args['orderby']        = 'post__in';
  $slide_args['posts_per_page'] = count($featured_ids);
}

$news_query = new WP_Query($slide_args);
$slide_posts = $news_query->posts ?: [];

get_header();
?>
<h1 class="hide-sitename"><?php echo get_post_field('post_title', $current_page_id); ?></h1>

<section class="news-hero-section">
  <div class="news-breadcrumb-wrap">
    <?php mona_output_breadcrumb(); ?>
  </div>

  <?php if (!empty($slide_posts)) : ?>
    <div class="news-hero-wrapper js-news-hero-swiper">
      <div class="swiper">
        <div class="swiper-wrapper">
            <?php foreach ($slide_posts as $sp) :
              $s_id = $sp->ID;
              $s_permalink = get_permalink($s_id);
              $s_image = get_the_post_thumbnail_url($s_id, 'full') ?: MONA_SITE_TEMPLATE_URL . '/assets/images/news/news-main.jpg';
              $s_cats = get_the_category($s_id);
              $s_cat_name = !empty($s_cats) ? $s_cats[0]->name : (function_exists('pll__') ? pll__('Tin nổi bật') : 'Tin nổi bật');
              $s_date = get_the_date('d/m/Y', $s_id);
            ?>
              <div class="swiper-slide">
                <div class="news-hero-card">
                  <!-- Left: Full-bleed Visual Image -->
                  <div class="news-hero-media">
                    <a href="<?php echo esc_url($s_permalink); ?>" class="news-hero-img-link" title="<?php echo esc_attr(get_the_title($s_id)); ?>">
                      <img src="<?php echo esc_url($s_image); ?>" alt="<?php echo esc_attr(get_the_title($s_id)); ?>" loading="lazy">
                    </a>
                  </div>

                  <!-- Right: Dark Luxury Content Block -->
                  <div class="news-hero-content">
                    <!-- Top Meta: Dot Grid Accent + Category + Date & External Link Icon -->
                    <div class="news-hero-meta-top">
                      <div class="news-hero-meta-left">
                        <div class="news-dot-pattern" aria-hidden="true">
                          <span></span><span></span><span></span><span></span>
                          <span></span><span></span><span></span><span></span>
                          <span></span><span></span><span></span><span></span>
                        </div>
                        <div class="news-hero-info-text">
                          <span class="news-hero-cat"><?php echo esc_html($s_cat_name); ?></span>
                          <time class="news-hero-date" datetime="<?php echo esc_attr(get_the_date('c', $s_id)); ?>"><?php echo esc_html($s_date); ?></time>
                        </div>
                      </div>
                      <a href="<?php echo esc_url($s_permalink); ?>" class="news-hero-ext-link" aria-label="<?php esc_attr_e('Xem chi tiết', 'monamedia'); ?>" title="<?php esc_attr_e('Xem chi tiết', 'monamedia'); ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                          <polyline points="15 3 21 3 21 9"></polyline>
                          <line x1="10" y1="14" x2="21" y2="3"></line>
                        </svg>
                      </a>
                    </div>

                    <!-- Middle: News Title & Excerpt -->
                    <div class="news-hero-body">
                      <h2 class="news-hero-title">
                        <a href="<?php echo esc_url($s_permalink); ?>">
                          <?php echo esc_html(get_the_title($s_id)); ?>
                        </a>
                      </h2>
                      <p class="news-hero-desc">
                        <?php echo esc_html(wp_trim_words(get_the_excerpt($s_id), 24, '...')); ?>
                      </p>
                    </div>

                    <!-- Bottom: Gold CTA Button & Nav Controls (< >) -->
                    <div class="news-hero-footer">
                      <a href="<?php echo esc_url($s_permalink); ?>" class="news-hero-btn">
                        <span><?php echo esc_html(function_exists('pll__') ? pll__('Xem chi tiết') : 'Xem chi tiết'); ?></span>
                      </a>

                      <div class="news-hero-nav">
                        <button type="button" class="news-nav-arrow js-news-hero-prev" aria-label="<?php esc_attr_e('Xem mục trước', 'monamedia'); ?>">
                          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                          </svg>
                        </button>
                        <button type="button" class="news-nav-arrow js-news-hero-next" aria-label="<?php esc_attr_e('Xem mục kế tiếp', 'monamedia'); ?>">
                          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                          </svg>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
</section>

<section class="news-group">
  <div class="container">
    <h2 class="main-tt"><?php echo esc_html(function_exists('pll__') ? pll__('Tin tức & Cập nhật') : 'Tin tức & Cập nhật'); ?></h2>
    <div class="news-tab">
      <div class="news-tab-inner js-tab-slide">
        <div class="swiper">
          <div class="swiper-wrapper">

            <div class="swiper-slide">
              <button class="tab-item is-active js-news-filter" data-taxonomy="category" data-term-id="">
                <?php echo esc_html(function_exists('pll__') ? pll__('Tất cả') : 'Tất cả'); ?>
              </button>
            </div>

            <?php
            $categories = get_categories([
              'taxonomy'   => 'category',
              'hide_empty' => true,
              'orderby'    => 'name',
              'order'      => 'DESC',
            ]);

            foreach ($categories as $category) :
            ?>
              <div class="swiper-slide">
                <button class="tab-item js-news-filter" data-taxonomy="category"
                  data-term-id="<?php echo esc_attr($category->term_id); ?>">
                  <?php echo esc_html($category->name); ?>
                </button>
              </div>
            <?php endforeach; ?>

          </div>
        </div>
      </div>
    </div>
    <div class="news-list js-news-list">
      <?php if (have_posts()) : ?>
        <?php while (have_posts()): the_post(); ?>
          <div class="news-box">
            <?php get_template_part('partials/components/loops/item', 'post'); ?>
          </div>
        <?php endwhile; ?>
        <div class="pagination js-news-pagination" data-aos="fade-up">
          <?php echo mona_pagination_links(); ?>
        </div>
      <?php else : ?>
        <p class="mona-empty"><?php echo esc_html(function_exists('pll__') ? pll__('Không tìm thấy dữ liệu') : 'Không tìm thấy dữ liệu'); ?></p>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php
get_footer();
