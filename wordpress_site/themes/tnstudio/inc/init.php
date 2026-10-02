<?php
defined('ABSPATH') || exit;

/********************
 * Load files (TN Studio Theme)
 *******************/
// classes
require_once TN_THEME_INC_PATH . '/classes/class.Mona_SetupTheme.php';
TN_SetupTheme::get_instance();

require_once TN_THEME_INC_PATH . '/classes/walkers/class.Mona_Walker_Nav_Menu.php';

// Functions
require_once TN_THEME_INC_PATH . '/functions/CommonFunction.php';
require_once TN_THEME_INC_PATH . '/functions/ImageFunction.php';
require_once TN_THEME_INC_PATH . '/functions/PaginationFunction.php';
require_once TN_THEME_INC_PATH . '/functions/PostFunction.php';
require_once TN_THEME_INC_PATH . '/functions/TaxonomyFunction.php';
require_once TN_THEME_INC_PATH . '/functions/Utils.php';
require_once TN_THEME_INC_PATH . '/functions/LangIdMap.php';

// ACF field groups
require_once TN_THEME_INC_PATH . '/acf/FieldGroupNews.php';
require_once TN_THEME_INC_PATH . '/acf/SubFieldLinks.php';
require_once TN_THEME_INC_PATH . '/acf/FieldGroupSlogan.php';

// Hooks
require_once TN_THEME_INC_PATH . '/hooks/CommonHook.php';
require_once TN_THEME_INC_PATH . '/hooks/ImageHook.php';
require_once TN_THEME_INC_PATH . '/hooks/PostHook.php';
require_once TN_THEME_INC_PATH . '/hooks/WidgetHook.php';

// Caches
require_once TN_THEME_INC_PATH . '/caches/MenuCache.php';

// Ajax
require_once TN_THEME_INC_PATH . '/ajax/PostAjax.php';
require_once TN_THEME_INC_PATH . '/ajax/ProjectAjax.php';
