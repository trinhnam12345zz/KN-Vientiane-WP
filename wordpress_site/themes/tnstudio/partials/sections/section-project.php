<?php
$taxonomy_slug = 'danh-muc';
$post_type_slug = 'du-an';

/**
 * Hàm hỗ trợ lấy Query nếu danh mục có bài viết
 */
function get_project_query_by_category($cat_slug, $taxonomy, $post_type)
{
  $term = get_term_by('slug', $cat_slug, $taxonomy);

  if ($term && $term->count > 0) {
    return new WP_Query([
      'post_type'      => $post_type,
      'posts_per_page' => 10,
      'tax_query'      => [
        [
          'taxonomy' => $taxonomy,
          'field'    => 'slug',
          'terms'    => $cat_slug,
        ],
      ],
    ]);
  }
  return false;
}

// Thực hiện lấy 3 câu query
$query_featured   = get_project_query_by_category('du-an-tieu-bieu', $taxonomy_slug, $post_type_slug);
$query_operating  = get_project_query_by_category('du-an-da-va-dang-van-hanh', $taxonomy_slug, $post_type_slug);
$query_developing = get_project_query_by_category('du-an-dang-phat-trien', $taxonomy_slug, $post_type_slug);
