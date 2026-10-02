<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Set post thumbnail default
 * 
 * @param string $html The post thumbnail HTML.
 * @param int $post_id The post ID.
 * @param int $post_thumbnail_id The post thumbnail ID, or 0 if there isn’t one.
 * @param string|int[] $size Requested image size. Can be any registered image size name, or an array of width and height values in pixels (in that order).
 * @param string|array $attr Query string or array of attributes.
 * 
 * @return string
 */
add_filter( 'post_thumbnail_html', function ($html, $post_id, $post_thumbnail_id, $size, $attr) {
    if ( empty( $html ) ) {
        return '<img src="' . MONA_THEME_PATH_URI . '/assets/images/default-thumbnail.jpg" alt="' . get_bloginfo('name') . '"/>';
    }

    return $html;
}, 20, 5 );