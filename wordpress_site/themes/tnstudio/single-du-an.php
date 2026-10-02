<?php
defined('ABSPATH') || exit;

get_header();

while (have_posts()) {
  the_post();

  $current_post_id = get_the_ID();

  $fields = get_fields($current_post_id);

  /**
   * Sections
   */
  $sections = [
    'oil' => $fields['oil'] ?? [],
    'other_projects' => $fields['section_other_projects'] ?? [],
    'contact' => $fields['section_contact'] ?? [],
  ];
?>

  <?php
  /**
   * section_detail
   */

  $old = $sections['oil'];
  ?>
  <?php mona_output_breadcrumb(); ?>
  <section class="news-detail royal-style">
    <div class="container">
      <?php
      // Category
      $categories = get_the_terms($current_post_id, 'danh-muc-du-an');
      // Featured image
      $thumbnail = get_the_post_thumbnail_url($current_post_id, 'full');
      // Share URL
      $current_url = urlencode(get_the_permalink());
      ?>

      <!-- Royal Hero Header (Idea 2) -->
      <div class="royal-hero-header">
        <!-- Category -->
        <?php if (!empty($categories) && !is_wp_error($categories)) : ?>
          <div class="post-tag">
            <span><?php echo esc_html($categories[0]->name); ?></span>
          </div>
        <?php endif; ?>

        <!-- Title -->
        <h1 class="royal-hero-title">
          <?php the_title(); ?>
        </h1>

        <div class="royal-hero-meta">
          <!-- Author -->
          <div class="meta-item author">
            <img src="/template/assets/images/news/user.svg" alt="author" loading="lazy">
            <span><?php the_author(); ?></span>
          </div>

          <span class="meta-dot">•</span>

          <!-- Date -->
          <div class="meta-item date">
            <img src="/template/assets/images/news/date.svg" alt="date" loading="lazy">
            <span><?php echo get_the_date('d/m/Y'); ?></span>
          </div>

          <span class="meta-dot">•</span>

          <!-- Share -->
          <div class="post-share-wrap">
            <span class="share-label"><?php echo esc_html(function_exists('pll__') ? pll__('Chia sẻ:') : 'Chia sẻ:'); ?></span>
            <ul class="p-ss-list">
              <li class="p-ss-item">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $current_url; ?>" target="_blank" title="Facebook">
                  <img src="/template/assets/images/news/ss1.svg" alt="facebook" loading="lazy">
                </a>
              </li>
              <li class="p-ss-item">
                <a href="<?php echo esc_url('https://zalo.me/share?url=' . rawurlencode(get_permalink())); ?>"
                  target="_blank" rel="noopener noreferrer" title="Zalo">
                  <img src="/template/assets/images/news/ss2.svg" alt="zalo" loading="lazy">
                </a>
              </li>
              <li class="p-ss-item">
                <button type="button" class="copy-link-btn" data-link="<?php echo esc_url($current_url); ?>" title="Copy link">
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
          <!-- Thumbnail -->
          <?php if ($thumbnail) : ?>
            <div class="post-img royal-post-img">
              <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
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
          $fields = get_fields(get_the_ID());
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
  /**
   * section_other_projects
   */
  $other_projects = $sections['other_projects'];
  if (!empty($other_projects['show'])) :
    $args = [
      'post_type'           => 'du-an',
      'post_status'         => 'publish',
      'posts_per_page'      => 12,
      'fields'              => 'ids',
      'post__not_in'        => [$current_post_id], // loại trừ bài hiện tại
      'orderby'             => 'date',
      'order'               => 'DESC',
    ];

    $project_query = new WP_Query($args);
  ?>

    <section class="news-req field-req">
      <div class="container">
        <div class="news-req-tt">
          <?php if (!empty($other_projects['title'])): ?>
            <h2 class="main-tt"><?php echo esc_html($other_projects['title']); ?></h2>
          <?php endif; ?>
          <?php if ($project_query->found_posts > 3) : ?>
            <div class="swiper-nav yel swiper-navigation">
              <div class="prev">
                <img src="/template/assets/images/icons/arrow-left-yel.svg" alt="" loading="lazy">
              </div>

              <div class="next">
                <img src="/template/assets/images/icons/arrow-left-yel.svg" alt="" loading="lazy">
              </div>
            </div>
          <?php endif; ?>
        </div>
        <div class="news-req-slide js-news-req">
          <div class="swiper">
            <div class="swiper-wrapper">
              <?php
              if ($project_query->have_posts()) :
              ?>

                <?php while ($project_query->have_posts()) : $project_query->the_post(); ?>
                  <div class="swiper-slide">
                    <?php get_template_part('partials/components/loops/item', 'project'); ?>
                  </div>
                <?php endwhile; ?>

              <?php
                wp_reset_postdata();
              else :
                echo '<div class="swiper-slide"><p>' . (function_exists('pll__') ? pll__('Chưa có dự án tiêu biểu nào.') : 'Chưa có dự án tiêu biểu nào.') . '</p></div>';
              endif;
              ?>
            </div>
          </div>
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </section>

  <?php endif; ?>

  <?php
  /**
   * section_contact
   */
  $contact = $sections['contact'];

  if (!empty($contact)) {
    mona_render_section($contact);
  }
  ?>
<?php
}

get_footer();
