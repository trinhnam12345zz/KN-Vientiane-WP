<?php
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

/**
 * Get primary term by taxonomy
 * 
 * @param int $post_id
 * @param string $taxonomy
 * 
 * @return mixed
 */
if ( ! function_exists( 'mona_get_primary_term' ) ) {
    function mona_get_primary_term( int $post_id, string $taxonomy = 'category' ) {
        if ( empty( get_post( $post_id ) ) ) return null;

        $result = null;

        // Get term
        $term_id = null;
        if ( is_plugin_active('wordpress-seo/wp-seo.php') ) {
            $term_id = yoast_get_primary_term_id( $taxonomy, $post_id );
        }

        if ( empty( $term_id ) ) {
            $terms = get_the_terms( $post_id, $taxonomy );

            if ( ! empty( $terms ) ) {
                $result = $terms[0];
            }
        }
        else {
            $result = get_term( $term_id, $taxonomy );
        }

        return $result;
    }
}