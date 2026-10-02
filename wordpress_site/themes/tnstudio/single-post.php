<?php
defined('ABSPATH') || exit;

get_header();
mona_output_breadcrumb();

while (have_posts()) {
  the_post();

  $post_id = get_the_ID();
  $primary_term = mona_get_primary_term($post_id, 'category');
?>
  <section class="news-detail royal-style">
    <div class="container" data-aos="fade-up">
      <!-- Royal Hero Header (Idea 2) -->
      <div class="royal-hero-header">
        <div class="post-tag">
          <?php if (! empty($primary_term) && ! is_wp_error($primary_term)) : ?>
            <a href="<?php echo esc_url(get_term_link($primary_term)); ?>"><?php echo esc_html($primary_term->name); ?></a>
          <?php else :
            $__cats = get_the_category();
            if (! empty($__cats)) : ?>
              <a href="<?php echo esc_url(get_category_link($__cats[0]->term_id)); ?>"><?php echo esc_html($__cats[0]->name); ?></a>
          <?php endif;
          endif; ?>
        </div>

        <h1 class="royal-hero-title"><?php the_title(); ?></h1>

        <div class="royal-hero-meta">
          <div class="meta-item author">
            <img src="/template/assets/images/news/user.svg" alt="" loading="lazy">
            <span><?php echo esc_html(get_the_author()); ?></span>
          </div>
          <span class="meta-dot">•</span>
          <div class="meta-item date">
            <img src="/template/assets/images/news/date.svg" alt="" loading="lazy">
            <span><?php echo esc_html(get_the_date('d/m/Y')); ?></span>
          </div>
          <span class="meta-dot">•</span>
          <div class="post-share-wrap">
            <span class="share-label"><?php echo esc_html(function_exists('pll__') ? pll__('Chia sẻ:') : 'Chia sẻ:'); ?></span>
            <ul class="p-ss-list">
              <li class="p-ss-item">
                <a href="<?php echo esc_url('https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode(get_permalink())); ?>"
                  target="_blank" rel="noopener noreferrer" title="Facebook">
                  <img src="/template/assets/images/news/ss1.svg" alt="" loading="lazy">
                </a>
              </li>
              <li class="p-ss-item">
                <a href="<?php echo esc_url('https://zalo.me/share?url=' . rawurlencode(get_permalink())); ?>"
                  target="_blank" rel="noopener noreferrer" title="Zalo">
                  <img src="/template/assets/images/news/ss2.svg" alt="" loading="lazy">
                </a>
              </li>
              <li class="p-ss-item">
                <button type="button" class="copy-link-btn" data-link="<?php echo esc_js(get_permalink()); ?>" title="Copy link">
                  <img src="/template/assets/images/news/ss3.svg" alt="copy link" loading="lazy">
                </button>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- 2-Column Content + Sidebar -->
      <div class="news-d-desc">
        <div class="news-d-txt">
          <?php if (has_post_thumbnail()) : ?>
            <div class="post-img royal-post-img">
              <?php the_post_thumbnail('full', [
                'loading' => 'eager',
                'fetchpriority' => 'high',
              ]); ?>
            </div>
          <?php endif; ?>

          <div class="mona-content">
            <?php
            ob_start();
            the_content();
            $content = ob_get_clean();

            if ($content) {
              echo $content;
            } else {
              echo '<p class="mona=empty">' . (function_exists('pll__') ? pll__('Nội dung sẽ sớm được cập nhật') : 'Nội dung sẽ sớm được cập nhật') . '</p>';
            }
            ?>
          </div>
        </div>

        <div class="btn-open-mb">
          <img src="/template/assets/images/icons/icon-cate.svg" alt="" title="" loading="lazy">
        </div>

        <div class="news-cate-group">
          <div class="btn-clost-mb">
            <img src="/template/assets/images/icons/close-btn.svg" alt="" title="" loading="lazy">
          </div>
          <?php
          $fields = get_fields($post_id);
          $old = $fields['oil'] ?? [];
          if (!empty($old['show'])) : ?>
            <div class="cate-box cate-highlight">
              <div class="btn-clost-mb">
                <img src="/template/assets/images/icons/close-btn.svg" alt="" title="" loading="lazy">
              </div>
              <div class="text-20"><?php echo esc_html(function_exists('pll__') ? pll__('Thông tin nổi bật') : 'Thông tin nổi bật'); ?></div>
              <ul class="ben-list">
                <?php foreach ($old['list'] as $item) : ?>
                  <li class="ben-item"><?php echo esc_html($item['title']); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>
          <?php if (is_active_sidebar('sidebar-detail-blog')) : ?>
            <?php dynamic_sidebar('sidebar-detail-blog'); ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <?php
  $options = [
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 6,
    'no_found_rows' => true,
    // 'meta_query' => ['relation' => 'AND'],
    // 'tax_query' => ['relation' => 'AND'],
    'post__not_in' => [$post_id],
  ];

  // if (! empty($primary_term) && ! is_wp_error($primary_term)) {
  //     $options['cat'] = $primary_term->term_id;
  // }

  $custom_query = new WP_Query($options);
  if ($custom_query->have_posts()) {
    if (! empty($primary_term) && ! is_wp_error($primary_term)) {
      $page_url = get_term_link($primary_term);
    } elseif (MONA_PAGE_BLOG) {
      $page_url = get_permalink(MONA_PAGE_BLOG);
    } else {
      $page_url = '';
    }
  ?>
    <section class="news-req">
      <div class="container" data-aos="fade-up">
        <div class="news-req-tt">
          <h2 class="main-tt"><?php echo esc_html(function_exists('pll__') ? pll__('Tin tức khác') : 'Tin tức khác'); ?></h2>
          <?php if ($custom_query->found_posts > 3) : ?>
            <div class="swiper-nav yel swiper-navigation">
              <div class="prev"> <img src="./assets/images/icons/arrow-left-yel.svg" alt="" title="" loading="lazy">
              </div>
              <div class="next"> <img src="./assets/images/icons/arrow-left-yel.svg" alt="" title="" loading="lazy">
              </div>
            </div>
          <?php endif; ?>
        </div>
        <div class="news-req-slide js-news-req">
          <div class="swiper">
            <div class="swiper-wrapper">
              <?php while ($custom_query->have_posts()) : $custom_query->the_post(); ?>
                <div class="swiper-slide">
                  <?php get_template_part('partials/components/loops/item', 'post'); ?>
                </div>
              <?php endwhile; ?>
            </div>
          </div>
        </div>
      </div>
    </section>
  <?php
  }
  wp_reset_postdata();
  ?>
<?php
}

get_footer();
