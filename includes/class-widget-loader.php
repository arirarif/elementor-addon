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
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
    }
    
    public function register_widgets($widgets_manager) {
        if (!class_exists('Elementor\Widget_Base')) {
            return;
        }
        
        require_once WHEELS_ELEMENTOR_PLUGIN_PATH . 'widgets/class-wheels-widget.php';
        $widgets_manager->register(new Wheels_Elementor_Widget());
    }
}