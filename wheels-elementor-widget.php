<?php
/**
 * Plugin Name: Wheels Elementor Widgets
 * Description: Wheels Elementor Widget
 * Version: 1.3.0
 * Author: Ivan Kuraev
 * Author URI: https://t.me/ivankuraev
 * Text Domain: wheels-elementor-widgets
 * Requires PHP: 8.3
 * Requires at least: 6.8
 */

if (!defined('ABSPATH')) {
    exit;
}

//
define('WHEELS_ELEMENTOR_VERSION', '1.3.0');
define('WHEELS_ELEMENTOR_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WHEELS_ELEMENTOR_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WHEELS_ELEMENTOR_PLUGIN_FILE', __FILE__);

// Проверка совместимости
if (!version_compare(PHP_VERSION, '8.3', '>=')) {
    add_action('admin_notices', 'wheels_elementor_php_version_notice');
} elseif (!did_action('elementor/loaded')) {
    add_action('admin_notices', 'wheels_elementor_fallback_notice');
} else {
    // Загрузка плагина
    require_once WHEELS_ELEMENTOR_PLUGIN_PATH . 'includes/class-plugin.php';
    Wheels_Elementor_Plugin::instance();
}

// Уведомление о версии PHP
function wheels_elementor_php_version_notice() {
    $message = sprintf(
        esc_html__('Плагин "Wheels Elementor Widgets" требует PHP версии %1$s или выше. Текущая версия: %2$s.', 'wheels-elementor-widgets'),
        '8.3',
        PHP_VERSION
    );
    printf('<div class="notice notice-error"><p>%s</p></div>', $message);
}

// Уведомление, если Elementor не активирован
function wheels_elementor_fallback_notice() {
    $message = sprintf(
        esc_html__('Плагин "Wheels Elementor Widgets" требует установленного и активированного %1$sElementor%2$s.', 'wheels-elementor-widgets'),
        '<a href="' . admin_url('plugin-install.php?tab=search&s=elementor') . '">',
        '</a>'
    );
    printf('<div class="notice notice-warning is-dismissible"><p>%s</p></div>', $message);
}