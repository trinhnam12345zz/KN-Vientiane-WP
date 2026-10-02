<?php
defined('ABSPATH') || exit;

if (empty($args['data']))
  return;
$data = $args['data'];

$mona_wl_lang = mona_ui_lang();

$mona_wl_title = !empty($data['title']) ? $data['title'] : 'DANH MỤC';
if (function_exists('pll__')) {
  $mona_wl_title = pll__($mona_wl_title);
}

$category_links = [];
if (!empty($data['list']) && is_array($data['list'])) {
  foreach ($data['list'] as $item) {
    if (!empty($item['link']) && is_array($item['link']) && !empty($item['link']['url'])) {
      $category_links[] = [
        'url' => $item['link']['url'],
        'title' => $item['link']['title'] ?? '',
        'target' => !empty($item['link']['target']) ? $item['link']['target'] : '_self',
      ];
    }
  }
}

// Fallback: If links are empty, fetch the main news categories automatically
if (empty($category_links)) {
  $terms = get_terms([
    'taxonomy' => 'category',
    'hide_empty' => false,
    'exclude' => [1, 32, 46, 60, 74], // exclude uncategorized
    'orderby' => 'term_id',
    'order' => 'ASC',
  ]);
  if (!empty($terms) && !is_wp_error($terms)) {
    foreach ($terms as $term) {
      $category_links[] = [
        'url' => get_term_link($term),
        'title' => $term->name,
        'target' => '_self',
      ];
    }
  }
}
?>

<?php if (!empty($category_links)) : ?>
  <div class="cate-box cate-category">
    <?php if (!empty($mona_wl_title)) : ?>
      <p class="text-20"><?php echo esc_html($mona_wl_title); ?></p>
    <?php endif; ?>

    <ul class="cate-list">
      <?php foreach ($category_links as $item) :
        $mona_wl_link_title = $item['title'];
        if (function_exists('pll__') && $mona_wl_link_title !== '') {
          $mona_wl_link_title = pll__($mona_wl_link_title);
        }
      ?>
        <li class="cate-item">
          <a class="<?php echo mona_is_current_url($item['url']) ? 'is-current' : ''; ?>"
            href="<?php echo esc_url($item['url']); ?>"
            target="<?php echo esc_attr($item['target']); ?>">
            <span><?php echo esc_html($mona_wl_link_title); ?></span>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>