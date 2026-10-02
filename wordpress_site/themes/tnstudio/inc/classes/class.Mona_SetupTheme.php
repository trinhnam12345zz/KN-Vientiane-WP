<?php
/**
 * TN Studio setup theme
 * 
 * @package TNStudio
 * @author  Trịnh Nam (TN Studio Lab)
 * @link    https://www.tnstudio.id.vn/
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'TN_SetupTheme' ) ) {
    class TN_SetupTheme {
        public static $instance = null;

        private function __construct() {
            // Setup theme features and hooks
            add_action( 'after_setup_theme', [ $this, 'after_setup_theme' ] );
            add_action( 'login_enqueue_scripts', [ $this, 'custom_login_page' ] );
            add_action( 'login_footer', [ $this, 'custom_login_footer' ] );
            
            // Add filters
            $this->add_filters();

            // Remove actions from wp_head
            $this->remove_wp_head_actions();

            $this->remove_block_assets();
        }

        public static function get_instance() {
            if ( ! self::$instance ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * Setup theme support features
         */
        public function after_setup_theme() {
            if ( ! current_user_can( 'administrator' ) && ! is_admin() ) {
                show_admin_bar( false );
            }
            
            load_theme_textdomain( 'tnstudio', get_template_directory() . '/languages' );
            load_theme_textdomain( 'monamedia', get_template_directory() . '/languages' );
            add_theme_support( 'post-thumbnails' );
            add_theme_support( 'woocommerce' );
            add_theme_support( 'title-tag' );
            add_theme_support( 'menus' );
            add_theme_support( 'html5', [ 'comment-list', 'search-form', 'comment-form' ] );
            add_theme_support( 'custom-logo', [
                'height'      => 100, 
                'width'       => 400, 
                'flex-height' => true, 
                'flex-width'  => true, 
                'header-text' => [ 'site-title', 'site-description' ],
            ]);
        }

        /**
         * Add custom filters
         */
        private function add_filters() {
            add_filter( 'wp_title', [ $this, 'rewrite_title_tag' ], 10, 3 );
            add_filter( 'the_generator', [ $this, 'remove_rss_version' ] );
            add_filter( 'the_content', [ $this, 'remove_ptags_on_images' ] );
            add_filter( 'get_the_archive_title', [ $this, 'rewrite_term_title' ] );
            add_filter( 'login_errors', [ $this, 'custom_wordpress_error_message' ] );
            add_filter( 'login_headerurl', [ $this, 'custom_login_headerurl' ] );
            add_filter( 'login_headertext', [ $this, 'custom_login_headertext' ] );
            add_filter( 'login_headertitle', [ $this, 'custom_login_headertext' ] );
            add_filter( 'login_message', [ $this, 'custom_login_message' ] );
            add_filter( 'admin_footer_text', [ $this, 'custom_admin_footer_text' ] );
            // add_filter( 'style_loader_src', [ $this, 'remove_version_from_scripts' ] );
            // add_filter( 'script_loader_src', [ $this, 'remove_version_from_scripts' ] );
            add_filter( 'mod_rewrite_rules', [ $this, 'rewrite_htaccess' ], 999999 );
            add_filter( 'upload_mimes', [ $this, 'custom_upload_mimes' ] );
            add_filter( 'login_display_language_dropdown', '__return_false' );
            add_filter( 'wpcf7_autop_or_not', '__return_false' );
            add_filter( 'xmlrpc_enabled', '__return_false' );
            add_filter( 'embed_oembed_discover', '__return_false' );
        }

        /**
         * Remove unnecessary actions from wp_head
         */
        private function remove_wp_head_actions() {
            remove_action( 'wp_head', 'wlwmanifest_link' );
            remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
            remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
            remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
            remove_action( 'wp_head', 'wp_generator' );
            remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
            remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
            remove_action( 'wp_head', 'wp_oembed_add_host_js' );
            remove_action( 'wp_head', 'rsd_link' );
            remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
            remove_action( 'rest_api_init', 'wp_oembed_register_route' );
        }

        /**
         * Custom WordPress error message on login page
         */
        public function custom_wordpress_error_message() {
            return __( 'Thông tin đăng nhập không chính xác...', 'tnstudio' );
        }

        /**
         * Remove version query from scripts and styles
         */
        public function remove_version_from_scripts( $src ) {
            if ( WP_DEBUG ) return $src;

            return strpos( $src, 'ver=' ) ? remove_query_arg( 'ver', $src ) : $src;
        }

        /**
         * Customize WordPress title tag
         */
        public function rewrite_title_tag( $title, $sep, $seplocation ) {
            global $page, $paged;

            if ( is_feed() ) return $title;

            $title .= ( 'right' == $seplocation ) ? get_bloginfo( 'name' ) : get_bloginfo( 'name' ) . $title;

            $site_description = get_bloginfo( 'description', 'display' );
            if ( $site_description && ( is_home() || is_front_page() ) ) {
                $title .= " {$sep} {$site_description}";
            }

            if ( $paged >= 2 || $page >= 2 ) {
                $title .= " {$sep} " . sprintf( __( 'Trang %s', 'tnstudio' ), max( $paged, $page ) );
            }

            return $title;
        }

        /**
         * Remove RSS generator version
         */
        public function remove_rss_version() {
            return '';
        }

        /**
         * Remove <p> tags from images
         */
        public function remove_ptags_on_images( $content ) {
            return preg_replace( '/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content );
        }

        /**
         * Rewrite archive titles
         */
        public function rewrite_term_title( $title ) {
            $strip_texts = [ 'Category:', 'Tag:', 'Tags:' ];
            return ( is_category() || is_tag() ) ? str_replace( $strip_texts, '', $title ) : $title;
        }

        /**
         * Customize rewrite rules
         */
        public function rewrite_htaccess( $rules ) {
            $custom_rules = "
                RewriteRule wp-content/plugins/(.*\.php)$ - [R=404,L]
                RewriteRule wp-content/themes/(.*\.php)$ - [R=404,L]
                RewriteCond %{QUERY_STRING} (<|%3C).*script.*(>|%3E) [NC,OR]
                RewriteCond %{QUERY_STRING} GLOBALS(=|[|%[0-9A-Z]{0,2}) [OR]
                RewriteCond %{QUERY_STRING} _REQUEST(=|[|%[0-9A-Z]{0,2})
                RewriteRule ^(.*)$ index.php [F,L]
                RewriteRule ^wp-admin/includes/ - [F,L]
                RewriteRule !^wp-includes/ - [S=3]
                RewriteRule ^wp-includes/[^/]+\.php$ - [F,L]
                RewriteRule ^wp-includes/js/tinymce/langs/.+\.php - [F,L]
                RewriteRule ^wp-includes/theme-compat/ - [F,L]
                RewriteRule ^wp-content/uploads/.*\.(php|rb|py)$ - [F,L,NC]
                RewriteRule ^wp-config.php$ - [F,L,NC]
            ";
            return str_replace( "</IfModule>", $custom_rules . "</IfModule>", $rules );
        }

        /**
         * Allow SVG uploads
         */
        public function custom_upload_mimes( $mimes ) {
            $mimes['svg'] = 'image/svg+xml';
            return $mimes;
        }

        /**
         * Custom login page styles (KN Vientiane Luxury Brand Design System)
         */
        public function custom_login_page() {
            if ( $GLOBALS['pagenow'] === 'wp-login.php' ) {
                wp_enqueue_style( 'kn-google-fonts-login', 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap', [], null );
                wp_enqueue_style( 'kn-style-login-template', TN_THEME_PATH_URI . '/assets/css/page-login.css', [], TN_THEME_VERSION );
            }
        }

        /**
         * Custom login logo URL -> Home Page
         */
        public function custom_login_headerurl() {
            return home_url('/');
        }

        /**
         * Custom login logo hover title
         */
        public function custom_login_headertext() {
            return get_bloginfo('name') . ' — Cổng Quản trị & Vận hành';
        }

        /**
         * Custom login message with KN Vientiane Luxury branding banner
         */
        public function custom_login_message( $message ) {
            $logo_url = MONA_SITE_TEMPLATE_URL . '/assets/images/header/header-logo-white.png';
            $brand_html = '
            <div class="kn-brand-header">
                <a class="kn-brand-logo-link" href="' . esc_url(home_url('/')) . '" title="' . esc_attr(get_bloginfo('name')) . '">
                    <img class="kn-brand-logo-img" src="' . esc_url($logo_url) . '" alt="' . esc_attr(get_bloginfo('name')) . '" />
                </a>
                <div class="kn-brand-badge-wrap">
                    <div class="kn-brand-status">
                        <span class="kn-status-dot"></span>
                        <span>CỔNG QUẢN TRỊ NỘI BỘ</span>
                    </div>
                </div>
            </div>';
            return $brand_html . $message;
        }

        /**
         * Custom login page footer
         */
        public function custom_login_footer() {
            echo '<div class="kn-login-footer">
                <p>© ' . date('Y') . ' <strong>KN VIENTIANE</strong>. All Rights Reserved.</p>
                <p class="kn-credit">Website được phát triển ban đầu bởi <strong>Mona Media</strong> • Tối ưu &amp; Tùy chỉnh bởi <a href="https://www.tnstudio.id.vn/" target="_blank" rel="noopener noreferrer" style="color: #2563eb !important; font-weight: 700 !important; text-decoration: none;"><span style="color: #2563eb !important; font-weight: 700 !important;">TN Studio Lab</span></a></p>
            </div>';
        }

        /**
         * Custom WP Admin dashboard footer text
         */
        public function custom_admin_footer_text() {
            return 'Hệ thống Quản trị &amp; Vận hành <strong>KN VIENTIANE</strong> — Phát triển ban đầu bởi <strong>Mona Media</strong>, Tối ưu &amp; Tùy chỉnh bởi <a href="https://www.tnstudio.id.vn/" target="_blank" rel="noopener noreferrer" style="color: #2563eb !important; font-weight: 700; text-decoration: none;">TN Studio Lab</a>.';
        }

        /**
         * Custom WP Admin dashboard version text
         */
        public function custom_admin_footer_version() {
            return '<span style="font-family: inherit; font-size: 11px; opacity: 0.9; color: #8c6d00; font-weight: 600;"><a class="tn-brand-link" href="https://www.tnstudio.id.vn/" target="_blank" rel="noopener noreferrer" style="color: #3b82f6; font-weight: 700; text-decoration: none;">TN Studio Core</a> • Portal v2.0</span>';
        }

        public function remove_block_assets() {
            // Disable Gutenberg for all post types
            add_filter('use_block_editor_for_post', '__return_false', 10);
            add_filter('use_block_editor_for_post_type', '__return_false', 10);

            // Disable block widgets
            add_action('init', function () {
                add_filter('use_widgets_block_editor', '__return_false');
                add_filter('gutenberg_use_widgets_block_editor', '__return_false');
            });

            // Remove Gutenberg CSS/JS
            add_action('wp_enqueue_scripts', function () {
                wp_dequeue_style('wp-block-library');
                wp_dequeue_style('wp-block-library-theme');
                wp_dequeue_style('wc-block-style'); // WooCommerce block styles
            }, 100);

            // Disable WooCommerce Blocks scripts
            add_filter('woocommerce_blocks_enqueue_scripts', '__return_false');
        }
    }

    // Backwards compatibility alias
    class_alias('TN_SetupTheme', 'Mona_SetupTheme');
}
