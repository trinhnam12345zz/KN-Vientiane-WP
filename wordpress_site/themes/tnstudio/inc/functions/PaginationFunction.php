<?php
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

/**
 * Render default pagination
 * 
 * @param WP_Query $wp_query
 * @param bool $echo
 * 
 * @return string
 */
if ( ! function_exists( 'mona_pagination_links' ) ) {
    function mona_pagination_links( $wp_query = null, $echo = false ) {
        if ( empty( $wp_query ) ) {
            global $wp_query;
        }

        if ( $wp_query->max_num_pages <= 1 ) {
            return;
        }

        $bignum = 999999999;

        // Output
        $output = '';
        $output .= '<div class="paginations">';
        $output .= paginate_links( [
            'base'      => str_replace( $bignum, '%#%', get_pagenum_link( $bignum, false ) ),
            'format'    => '',
            'current'   => max( 1, get_query_var( 'paged' ) ),
            'total'     => $wp_query->max_num_pages,
            'prev_text' => '<img src="/template/assets/images/icons/chevron-right.svg" alt="Arrow previous" title="Arrow previous" loading="lazy" />',
            'next_text' => '<img src="/template/assets/images/icons/chevron-right.svg" alt="Arrow next" title="Arrow next" loading="lazy" />',
            'type'      => 'list',
            'end_size'  => 2,
            'mid_size'  => 3
        ] );
        $output .= '</div>';

        if ( $echo ) {
            echo $output;
        }
        else {
            return $output;
        }
    }
}

/**
 * Pagination for ajax
 * 
 * @param WP_Query $wp_query
 * @param int $paged
 * @param array $attributes Add data attributes to html element
 * @param bool $is_output_wrapper
 * @param bool $echo
 * 
 * @return string
 */
if ( ! function_exists( 'mona_pagination_links_ajax' ) ) {
    function mona_pagination_links_ajax( $wp_query = null, $paged = 1, $attributes = array(), $is_output_wrapper = false, $echo = false ) {
        if ( empty( $wp_query ) ) {
            global $wp_query;
        }
    
        if ( $wp_query->max_num_pages <= 1 ) {
            return '';
        }
    
        $bignum = 999999999;
        $current_page = max( 1, $paged );

        // Set attributes
        $attribute_data = '';
        if ( ! empty( $attributes ) ) {
            foreach ( $attributes as $key => $value ) {
                $attribute_data .= "data-{$key}=\"{$value}\"";
            }
        }

        // Render
        $output = '<div class="pagination ajax-pagination" ' . $attribute_data . '>';
        ob_start(); // Start output buffering
    
        echo paginate_links( [
            'base'      => str_replace( $bignum, '%#%',  $bignum ),
            'format'    => '',
            'current'   => $current_page,
            'total'     => $wp_query->max_num_pages,
            'prev_text' => '<img src="/template/assets/images/icons/chevron-right.svg" alt="Arrow previous" title="Arrow previous" loading="lazy" />',
            'next_text' => '<img src="/template/assets/images/icons/chevron-right.svg" alt="Arrow next" title="Arrow next" loading="lazy" />',
            'type'      => 'list',
            'end_size'  => 2,
            'mid_size'  => 3,
            'before_page_number' => '<span class="page-numbers-ajax">',
            'after_page_number'  => '</span>',
        ] );

        $output .= ob_get_clean();
        $output .= '</div>';

        $output = preg_replace( '/href="http:\/\/(\d+)"/', 'href="#" data-href="$1"', $output );
        $output = preg_replace( '/href="http:\/\/(\d+)[^"]*"/', 'href="#" data-href="$1"', $output );
        $output = preg_replace( '/data-paged="%#%"/', 'data-paged="' . $current_page . '"', $output );

        // Need wrapper
        if ( $is_output_wrapper ) {
            $output = '<div id="ajax-pagination">' . $output . '</div>';
        }

        if ( $echo ) {
            echo $output;
        }
        else {
            return $output;
        }
    }
}