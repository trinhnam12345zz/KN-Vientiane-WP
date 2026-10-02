<?php

/**
 * Loop item post
 * 
 * @author MONA.Media / Website
 */

$post_id = get_the_ID();
$post_type = get_post_type();
$permalink = get_the_permalink();

$primary_term = mona_get_primary_term($post_id, 'category');
?>

<article class="news-item">
  <a class="news-img" href="<?php echo $permalink; ?>">
    <div class="img-box">
      <?php the_post_thumbnail('large', [
        'loading' => 'lazy',
      ]); ?>
    </div>
  </a>
  <div class="n-short-info">
    <div class="ns-item">
      <img src="/template/assets/images/news/user.svg" alt="" title="" loading="lazy">
      <p> <?php the_author(); ?></p>
    </div>
    <div class="ns-item">
      <img src="/template/assets/images/news/date.svg" alt="" title="" loading="lazy">
      <p><?php echo get_the_date('d/m/Y'); ?></p>
    </div>
  </div>
  <a class="text-16 fw-sb" href="<?php echo $permalink; ?>">
    <?php the_title(); ?>
  </a>
  <p class="text-14"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
  <a class="n-view-more" href="<?php echo $permalink; ?>">
    <span><?php echo esc_html(function_exists('pll__') ? pll__('Xem chi tiết') : 'Xem chi tiết'); ?> </span>
    <img src="/template/assets/images/news/arr-view.svg" alt="" title="" loading="lazy">
  </a>
</article>