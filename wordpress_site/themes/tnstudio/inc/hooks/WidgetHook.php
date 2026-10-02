<?php
defined('ABSPATH') || exit;

add_action('after_setup_theme', function () {
    register_sidebar([
        'name' => __('Sidebar chi tiết tin tức', 'tnstudio'),
        'id' => 'sidebar-detail-blog',
        'description' => '',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ]);

    register_sidebar([
        'name' => __('Sidebar danh mục tin tức', 'tnstudio'),
        'id' => 'sidebar-category-blog',
        'description' => '',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ]);

    register_sidebar([
        'name' => __('Sidebar tin tức', 'tnstudio'),
        'id' => 'sidebar-blog',
        'description' => '',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ]);

    register_sidebar([
        'name' => __('Sidebar tuyển dụng', 'tnstudio'),
        'id' => 'sidebar-benefit-job',
        'description' => '',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ]);
}, 10);

class TNLinksWidget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'mona_links_widget',
            __('Danh sách liên kết', 'tnstudio'),
            []
        );
    }

    public function widget($args, $instance)
    {
        $data = get_fields('widget_' . $args['widget_id']);
        if (empty($data)) return;

        get_template_part('partials/widgets/widget', 'links', [
            'instance' => $instance,
            'data' => $data,
        ]);
    }

    public function form($instance) {}
}
class_alias('TNLinksWidget', 'MonaLinksWidget');

class TNPromotionWidget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'mona_promotion_widget',
            'Promotion',
            []
        );
    }

    public function widget($args, $instance)
    {
        $data = get_fields('widget_' . $args['widget_id']);
        if (empty($data)) return;

        get_template_part('partials/widgets/widget', 'promotion', [
            'instance' => $instance,
            'data' => $data,
        ]);
    }

    public function form($instance) {}
}
class_alias('TNPromotionWidget', 'MonaPromotionWidget');

class TNRecentPostWidget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'mona_recent_post_widget',
            __('Danh sách bài viết', 'tnstudio'),
            []
        );
    }

    public function widget($args, $instance)
    {
        $data = get_fields('widget_' . $args['widget_id']);
        if (empty($data)) return;

        get_template_part('partials/widgets/widget', 'recent_post', [
            'instance' => $instance,
            'data' => $data,
        ]);
    }

    public function form($instance) {}
}
class_alias('TNRecentPostWidget', 'MonaRecentPostWidget');

class TNCategoriesWidget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'mona_categories_widget',
            __('Danh sách danh mục', 'tnstudio'),
            []
        );
    }

    public function widget($args, $instance)
    {
        $data = get_fields('widget_' . $args['widget_id']);
        if (empty($data)) return;

        get_template_part('partials/widgets/widget', 'category', [
            'instance' => $instance,
            'data' => $data,
        ]);
    }

    public function form($instance) {}
}
class_alias('TNCategoriesWidget', 'MonaCategorysWidget');

class TNJobBenefitWidget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'mona_job_benefit_widget',
            __('Phúc lợi công ty', 'tnstudio'),
            []
        );
    }

    public function widget($args, $instance)
    {
        $data = get_fields('widget_' . $args['widget_id']);
        if (empty($data)) return;

        get_template_part('partials/widgets/widget', 'benefit', [
            'instance' => $instance,
            'data' => $data,
        ]);
    }

    public function form($instance) {}
}
class_alias('TNJobBenefitWidget', 'MonaJobBenefitWidget');

add_action('widgets_init', function () {
    // Tắt các widget mặc định của WordPress
    $default_widgets = [
        'WP_Widget_Pages',
        'WP_Widget_Calendar',
        'WP_Widget_Archives',
        'WP_Widget_Links',
        'WP_Widget_Meta',
        'WP_Widget_Search',
        'WP_Widget_Text',
        'WP_Widget_Categories',
        'WP_Widget_Recent_Posts',
        'WP_Widget_Recent_Comments',
        'WP_Widget_RSS',
        'WP_Widget_Tag_Cloud',
        'WP_Nav_Menu_Widget',
        'WP_Widget_Custom_HTML',
        'WP_Widget_Media_Audio',
        'WP_Widget_Media_Image',
        'WP_Widget_Media_Video',
        'WP_Widget_Media_Gallery',
        'WP_Widget_Block',
    ];

    foreach ($default_widgets as $widget) {
        unregister_widget($widget);
    }

    $widgets = [
        TNLinksWidget::class,
        TNPromotionWidget::class,
        TNRecentPostWidget::class,
        TNCategoriesWidget::class,
        TNJobBenefitWidget::class,
    ];

    foreach ($widgets as $widget) {
        register_widget($widget);
    }
}, 11);