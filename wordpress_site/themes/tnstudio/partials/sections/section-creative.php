<?php
defined('ABSPATH') || exit;

if (!isset($args['object_id']))
  return;

$data = get_field('section_creative_value', $args['object_id']);
if (empty($data))
  return;
?>

<section class="slogan">
  <div class="slogan-bg">
    <?php
    $media_type = $data['images_or_video'] ?? '';

    if ($media_type == 'image'):
      // Lấy và hiển thị field hình ảnh
      $image = $data['image'];
      if ($image): ?>
        <?php echo wp_get_attachment_image($image, 'full', false, [
          'loading' => 'lazy',
        ]); ?>
      <?php endif;

    elseif ($media_type == 'video'):
      // Lấy và hiển thị field video
      $video = $data['video'];
      $video_file_url = $video
        ? wp_get_attachment_url($video)
        : '';
      if ($video_file_url): ?>
        <video width="100%" height="100%" style="object-fit: cover;" autoplay loop muted controls>
          <source src="<?php echo esc_url($video_file_url); ?>" type="<?php echo esc_attr($video['mime_type']); ?>">
        </video>
    <?php endif;

    endif;
    ?>
  </div>
  <div class="slogan-tt">
    <div class="container">
      <?php if (!empty($data['title'])) : ?>
        <h2 class="title-64"><?php echo esc_html($data['title']); ?></h2>
      <?php endif; ?>
    </div>
  </div>
</section>