<?php
/**
 * Plugin Name: Wheels Elementor Widgets
 * Description: Wheels Elementor Widget
 * Version: 1.3.0
 * Author: Ivan Kuraev
 * Author URI: https://t.me/ivankuraev
 * Text Domain: wheels-elementor-widgets
 * Requires at least: 5.0
 */

if (!defined('ABSPATH')) {
    exit;
}

//
define('WHEELS_ELEMENTOR_VERSION', '1.3.0');
define('WHEELS_ELEMENTOR_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WHEELS_ELEMENTOR_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WHEELS_ELEMENTOR_PLUGIN_FILE', __FILE__);

// Initialize plugin after all plugins are loaded
add_action('plugins_loaded', 'wheels_elementor_init');

function wheels_elementor_init() {
    // Check if Elementor is loaded
    if (!did_action('elementor/loaded')) {
        add_action('admin_notices', 'wheels_elementor_fallback_notice');
        return;
    }

    // Load the plugin
    require_once WHEELS_ELEMENTOR_PLUGIN_PATH . 'includes/class-plugin.php';
    Wheels_Elementor_Plugin::instance();
}

// Notice if Elementor is not activated
function wheels_elementor_fallback_notice() {
    $message = sprintf(
        esc_html__('Плагин "Wheels Elementor Widgets" требует установленного и активированного %1$sElementor%2$s.', 'wheels-elementor-widgets'),
        '<a href="' . admin_url('plugin-install.php?tab=search&s=elementor') . '">',
        '</a>'
    );
    printf('<div class="notice notice-warning is-dismissible"><p>%s</p></div>', $message);
}