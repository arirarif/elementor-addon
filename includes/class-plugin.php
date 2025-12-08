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
    }
    
    public function init_elementor() {
        // Добавляем категорию виджетов
        add_action('elementor/elements/categories_registered', [$this, 'add_widget_category']);
        
        // Регистрируем виджеты
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
            ['jquery'],
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
}