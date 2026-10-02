<?php
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

/**
 * Get image id by url
 * 
 * @param string $image_url
 * @return mixed
 */
if ( ! function_exists( 'mona_get_image_id_by_url' ) ) {
    function mona_get_image_id_by_url( $image_url = '' ) {
        if ( empty ( $image_url ) ) {
            return false;
        }

        global $wpdb;
        $attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ) );

        if ( ! empty ( $attachment ) ) {
            return $attachment[0];
        }

        return false;
    }
}

/**
 * Adds a <link rel="preload"> tag to preload an image.
 *
 * @param string $img_url The URL of the image to preload.
 * @return void
 */
if ( ! function_exists( 'mona_add_image_preload_link' ) ) {
    function mona_add_image_preload_link($img_url) {
        // Exit if image URL is empty
        if (empty($img_url)) {
            return; 
        }
        
        // Get image type
        $img_type = wp_check_filetype($img_url);
        
        // Output <link rel="preload"> tag with image URL and metadata
        echo '<link rel="preload" fetchpriority="high" as="image" href="' . esc_url($img_url) . '" type="' . esc_attr($img_type['type']) . '">';
    }
}

/**
 * Get image by id
 * 
 * @param int $id
 * @param string $size
 * @param bool $is_icon
 * @param array $args Attributes
 * 
 * @return string
 */
if ( ! function_exists( 'mona_get_image_by_id' ) ) {
    function mona_get_image_by_id( $id, $size = 'full', $is_icon = false, $args = array() ) {
        // Attributes
        $default_attributes = array(
            'loading' => 'lazy',
        );

        $image_src = wp_get_attachment_image_src($id, $size, $is_icon);

        if ( empty( $image_src ) ) {
            return '<img src="' . MONA_THEME_PATH_URI . '/assets/images/default-thumbnail.jpg" alt="' . get_bloginfo('name') . '" />';
        }

        $attributes = wp_parse_args($args, $default_attributes);

        return wp_get_attachment_image($id, $size, $is_icon, $attributes);
    }
}