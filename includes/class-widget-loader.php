<?php
if (!defined('ABSPATH')) {
    exit;
}

class Wheels_Elementor_Widget_Loader {
    
    private static $_instance = null;
    
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    private function __construct() {
        // Elementor 3.5+ uses 'elementor/widgets/register'
        // Older versions use 'elementor/widgets/widgets_registered'
        if (defined('ELEMENTOR_VERSION') && version_compare(ELEMENTOR_VERSION, '3.5.0', '>=')) {
            add_action('elementor/widgets/register', [$this, 'register_widgets']);
        } else {
            add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets_legacy']);
        }
    }

    public function register_widgets($widgets_manager) {
        if (!class_exists('Elementor\Widget_Base')) {
            return;
        }

        require_once WHEELS_ELEMENTOR_PLUGIN_PATH . 'widgets/class-wheels-widget.php';
        $widgets_manager->register(new Wheels_Elementor_Widget());
    }

    // Legacy method for Elementor < 3.5
    public function register_widgets_legacy() {
        if (!class_exists('Elementor\Widget_Base')) {
            return;
        }

        require_once WHEELS_ELEMENTOR_PLUGIN_PATH . 'widgets/class-wheels-widget.php';
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Wheels_Elementor_Widget());
    }
}