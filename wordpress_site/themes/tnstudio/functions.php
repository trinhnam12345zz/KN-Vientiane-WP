<?php

declare(strict_types=1);

/**
 * TN Studio Theme Functions
 *
 * @package TN Studio Lab / Website
 * @author  Trịnh Nam (TN Studio Lab)
 * @link    https://www.tnstudio.id.vn/
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

// Define TN Studio theme information
define('TN_THEME_VERSION', '4.6.7');
define('TN_THEME_PATH', get_template_directory());
define('TN_THEME_PATH_URI', get_template_directory_uri());
define('TN_THEME_INC_PATH', TN_THEME_PATH . '/inc');
define('TN_THEME_CONFIG_PATH', TN_THEME_PATH . '/configs');
define('TN_SITE_URL', get_option('siteurl'));
define('TN_SITE_TEMPLATE_URL', TN_SITE_URL . '/template');

// Define TN Studio theme page settings
define('TN_PAGE_HOME', get_option('page_on_front', true));
define('TN_PAGE_BLOG', get_option('page_for_posts', true));
define('TN_CUSTOM_LOGO', get_theme_mod('custom_logo'));
define('TN_POSTS_PER_PAGE', get_option('posts_per_page', 6));

// Backwards compatibility aliases for existing templates
if (!defined('MONA_THEME_VERSION')) define('MONA_THEME_VERSION', TN_THEME_VERSION);
if (!defined('MONA_THEME_PATH')) define('MONA_THEME_PATH', TN_THEME_PATH);
if (!defined('MONA_THEME_PATH_URI')) define('MONA_THEME_PATH_URI', TN_THEME_PATH_URI);
if (!defined('MONA_THEME_INC_PATH')) define('MONA_THEME_INC_PATH', TN_THEME_INC_PATH);
if (!defined('MONA_THEME_CONFIG_PATH')) define('MONA_THEME_CONFIG_PATH', TN_THEME_CONFIG_PATH);
if (!defined('MONA_SITE_URL')) define('MONA_SITE_URL', TN_SITE_URL);
if (!defined('MONA_SITE_TEMPLATE_URL')) define('MONA_SITE_TEMPLATE_URL', TN_SITE_TEMPLATE_URL);
if (!defined('MONA_PAGE_HOME')) define('MONA_PAGE_HOME', TN_PAGE_HOME);
if (!defined('MONA_PAGE_BLOG')) define('MONA_PAGE_BLOG', TN_PAGE_BLOG);
if (!defined('MONA_CUSTOM_LOGO')) define('MONA_CUSTOM_LOGO', TN_CUSTOM_LOGO);
if (!defined('MONA_POSTS_PER_PAGE')) define('MONA_POSTS_PER_PAGE', TN_POSTS_PER_PAGE);

require_once __DIR__ . '/inc/init.php';