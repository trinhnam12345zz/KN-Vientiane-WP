<?php

/**
 * The template for displaying category.
 *
 * @package MONA.Media / Website
 */

defined('ABSPATH') || exit;

$current_term = get_queried_object();

get_header();


?>

<section class="banner-main">
  <?php mona_output_breadcrumb(); ?>
  <div class="container">
    <div class="banner-info">
      <div class="logo-mark"> <img src="/template/assets/images/news/logo-banner.png" alt="" title="" loading="lazy">
      </div>
      <h2 class="main-tt"><?php echo $current_term->name; ?></h2>
    </div>
  </div>
</section>

<section class="news-cate">
  <div class="container">
    <div class="news-cate-inner">
      <div class="btn-open-mb"> <img src="/template/assets/images/icons/icon-cate.svg" alt="" title="" loading="lazy">
      </div>
      <div class="news-cate-group">
        <div class="btn-clost-mb">
          <img src="/template/assets/images/icons/close-btn.svg" alt="" title="" loading="lazy">
        </div>
        <?php
        // Render category sidebar in the current WPML language.
        $mona_current_language = mona_ui_lang();
        $mona_cate_title = function_exists('pll__') ? pll__('DANH MỤC') : 'DANH MỤC';
        $mona_cate_list = get_categories([
          'taxonomy'   => 'category',
          'hide_empty' => false,
          'exclude'    => [get_option('default_category')],
          'orderby'    => 'term_id',
          'order'      => 'ASC',
        ]);
        // Filter out any leftover "Uncategorized" translations.
        $mona_cate_list = array_values(array_filter($mona_cate_list, static function ($cat) {
          $slug = strtolower($cat->slug);
          $name = strtolower($cat->name);
          if ($slug === 'uncategorized' || strpos($slug, 'uncategorized') === 0) return false;
          if ($name === 'uncategorized' || $name === 'chưa phân loại') return false;
          return true;
        }));
        ?>
        <div class="cate-box cate-category">
          <p class="text-20"><?php echo esc_html($mona_cate_title); ?></p>
          <?php if (!empty($mona_cate_list)) : ?>
          <ul class="cate-list">
            <?php foreach ($mona_cate_list as $mona_cat) :
              $mona_cat_link = get_category_link($mona_cat->term_id);
              $mona_is_current = (isset($current_term->term_id) && (int) $current_term->term_id === (int) $mona_cat->term_id);
              ?>
              <li class="cate-item">
                <a class="<?php echo $mona_is_current ? 'is-current' : ''; ?>" href="<?php echo esc_url($mona_cat_link); ?>">
                  <span><?php echo esc_html($mona_cat->name); ?></span>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
        </div>
      </div>
      <div class="news-cate-list">
        <div class="news-list">
          <?php if (have_posts()) : ?>
          <?php while (have_posts()): the_post(); ?>
          <div class="news-box">
            <?php get_template_part('partials/components/loops/item', 'post'); ?>
          </div>
          <?php endwhile; ?>
          <div class="pagination" data-aos="fade-up">
            <?php echo mona_pagination_links(); ?>
          </div>
          <?php else : ?>
          <p class="mona-empty"><?php echo esc_html(function_exists('pll__') ? pll__('Không tìm thấy dữ liệu') : 'Không tìm thấy dữ liệu'); ?></p>
          <?php endif; ?>
        </div>
        <div class="pagination">
          <?php echo mona_pagination_links(); ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
get_footer();