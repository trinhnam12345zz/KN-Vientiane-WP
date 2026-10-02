<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// Remove image attributes
add_filter( 'wp_get_attachment_image_attributes', function ( $attr ) {
    unset( $attr['sizes'] );
    return $attr;
}, 10, 1 );