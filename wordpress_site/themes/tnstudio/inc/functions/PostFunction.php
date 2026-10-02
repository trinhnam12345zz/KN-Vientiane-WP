<?php
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

/**
 * Set post view
 * 
 * @param int $post_id
 */
if ( ! function_exists( 'mona_set_post_view' ) ) {
    function mona_set_post_view( $post_id = 0 ) {
        if ( empty ( $post_id ) ) {
            $post_id = get_the_ID();
        }

        // Check exists
        $count_key = '_mona_post_view';
        $count = get_post_meta( $post_id, $count_key, true );

        if ( $count == '' ) {
            $count = 1;
            delete_post_meta( $post_id, $count_key );
            add_post_meta( $post_id, $count_key, $count );
        }
        else {
            $count++;
            update_post_meta( $post_id, $count_key, $count );
        }
    }
}

/**
 * Get post view
 * 
 * @param int $post_id
 * @return int
 */
if ( ! function_exists( 'mona_get_post_view' ) ) {
    function mona_get_post_view( $post_id = 0 ) {
        if ( empty ( $post_id ) ) {
            $post_id = get_the_ID();
        }

        // Check exist
        $count_key = '_mona_post_view';
        $count = get_post_meta( $post_id, $count_key, true );
        if ( $count == '' ) {
            delete_post_meta( $post_id, $count_key );
            add_post_meta( $post_id, $count_key, 0 );
            return 0;
        }
    
        return $count;
    }
}