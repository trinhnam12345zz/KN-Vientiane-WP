<?php
/**
 * TN Studio custom nav menu walker
 * 
 * @author TN Studio Lab
 * @link   https://www.tnstudio.id.vn/
 */
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

if ( ! class_exists( 'TN_Walker_Nav_Menu' ) ) {
    class TN_Walker_Nav_Menu extends Walker_Nav_Menu {
        function start_lvl(&$output, $depth = 0, $args = array()) {
            $indent = str_repeat("\t", $depth);
            $output .= "\n$indent<ul class='child js-child{$depth}'>\n";
        }

        function end_lvl(&$output, $depth = 0, $args = array()) {
            $indent = str_repeat("\t", $depth);
            $output .= "$indent</ul>\n";
        }

        function start_el( &$output, $item, $depth=0, $args=array(), $id = 0 ) {
            $title = apply_filters( 'the_title', $item->title, $item->ID );
            $title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
            $permalink = function_exists('mona_localize_url') ? mona_localize_url($item->url) : $item->url;

            $classes = empty($item->classes) ? array() : (array) $item->classes;

            // Resolve active state for all languages
            $is_current = false;

            $req_path  = trailingslashit(strtok($_SERVER['REQUEST_URI'] ?? '', '?'));
            $item_path = trailingslashit(wp_parse_url($permalink, PHP_URL_PATH) ?: '');

            if ($item_path && $item_path === $req_path) {
                $is_current = true;
            } else {
                $queried_id  = (int) get_queried_object_id();
                $item_obj_id = (int) $item->object_id;

                if ($queried_id && $item_obj_id) {
                    if ($queried_id === $item_obj_id) {
                        $is_current = true;
                    } elseif (function_exists('pll_get_post_translations')) {
                        $tr = pll_get_post_translations($item_obj_id);
                        if (is_array($tr) && in_array($queried_id, $tr, true)) {
                            $is_current = true;
                        }
                    }
                }

                // Check post type archives (e.g. tuyen-dung)
                if (! $is_current && $item->type === 'post_type_archive') {
                    $pt = $item->object;
                    if ($pt && (is_post_type_archive($pt) || is_singular($pt))) {
                        $is_current = true;
                    }
                }
            }

            if ($is_current) {
                if (! in_array('current-menu-item', $classes, true)) {
                    $classes[] = 'current-menu-item';
                }
                if (! in_array('current_page_item', $classes, true)) {
                    $classes[] = 'current_page_item';
                }
            }

            // Check has children
            $has_children = in_array('menu-item-has-children', $classes);

            $output .= "<li class='" .  implode(" ", $classes) . "'>";

            //Add SPAN if no Permalink
            if ( $permalink && $permalink != '#' ) {
                $output .= '<a class="menu-link" href="' . $permalink . '" title="' . $title . '">';
            } else {
                $output .= '<a class="menu-link" href="javascript:;" title="' . $title . '">';
            }

            $output .= $title;

            $output .= '</a>';

            // If has children
            if ($has_children) {
                $output .= '<img src="/assets/images/icons/down.svg" alt="Icon down" loading="lazy">';
            }
        }
    }

    class_alias('TN_Walker_Nav_Menu', 'Mona_Walker_Nav_Menu');
}
