<?php
if (!defined('ABSPATH')) {
    exit;
}

final class Wheels_Elementor_Plugin {

    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {
        $this->includes();
        $this->init_hooks();
    }

    private function includes() {
        require_once WHEELS_ELEMENTOR_PLUGIN_PATH . 'includes/class-widget-loader.php';
    }

    private function init_hooks() {
        add_action('elementor/init', [$this, 'init_elementor']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        add_action('init', [$this, 'load_plugin_textdomain']);

        // Filter products by ACF meta values
        add_action('pre_get_posts', [$this, 'filter_products_by_meta'], 20);
    }

    public function init_elementor() {
        add_action('elementor/elements/categories_registered', [$this, 'add_widget_category']);
        Wheels_Elementor_Widget_Loader::instance();
    }

    public function add_widget_category($elements_manager) {
        $elements_manager->add_category(
            'wheels-category',
            [
                'title' => __('Wheels Block Widgets', 'wheels-elementor-widgets'),
                'icon' => 'fa fa-plug',
            ]
        );
    }

    public function enqueue_frontend_assets() {
        wp_enqueue_style(
            'wheels-elementor-frontend',
            WHEELS_ELEMENTOR_PLUGIN_URL . 'assets/css/frontend.css',
            [],
            WHEELS_ELEMENTOR_VERSION
        );

        wp_enqueue_script(
            'wheels-elementor-frontend',
            WHEELS_ELEMENTOR_PLUGIN_URL . 'assets/js/frontend.js',
            [],
            WHEELS_ELEMENTOR_VERSION,
            true
        );
    }

    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            'wheels-elementor-widgets',
            false,
            dirname(plugin_basename(WHEELS_ELEMENTOR_PLUGIN_FILE)) . '/languages/'
        );
    }

    /**
     * Filter products by meta_ URL parameters
     */
    public function filter_products_by_meta($query) {
        // Only run on frontend, main query, and shop/product pages
        if (is_admin()) {
            return;
        }

        if (!$query->is_main_query()) {
            return;
        }

        // Check if we're on shop page or product archive
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            // Also check post_type parameter
            if (!isset($_GET['post_type']) || $_GET['post_type'] !== 'product') {
                return;
            }
        }

        // Get meta parameters from URL
        $meta_params = $this->get_meta_params();

        if (empty($meta_params)) {
            return;
        }

        // Build meta query
        $meta_query = $query->get('meta_query');
        if (!is_array($meta_query)) {
            $meta_query = [];
        }

        foreach ($meta_params as $key => $value) {
            $meta_query[] = [
                'key' => $key,
                'value' => $value,
                'compare' => '='
            ];
        }

        // Set relation if multiple conditions
        if (count($meta_params) > 1) {
            $meta_query['relation'] = 'AND';
        }

        $query->set('meta_query', $meta_query);

        // Make sure we're querying products
        $query->set('post_type', 'product');
    }

    /**
     * Get meta_ parameters from URL
     */
    private function get_meta_params() {
        $meta_params = [];

        foreach ($_GET as $key => $value) {
            if (strpos($key, 'meta_') === 0 && !empty($value)) {
                // Remove 'meta_' prefix to get the actual field name
                $field_name = substr($key, 5);
                $meta_params[$field_name] = sanitize_text_field($value);
            }
        }

        return $meta_params;
    }
}
