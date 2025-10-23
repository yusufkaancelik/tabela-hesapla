<?php
/**
 * Plugin Name: Tabela Hesapla
 * Plugin URI: https://github.com/yusufkaancelik/tabela-hesapla
 * Description: Tabela fiyatlarını otomatik hesaplayan WordPress eklentisi
 * Version: 1.0.0
 * Author: Yusuf Kaan Çelik
 * Text Domain: tabela-hesapla
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('VC_TABELA_VERSION', '1.0.0');
define('VC_TABELA_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('VC_TABELA_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once VC_TABELA_PLUGIN_DIR . 'includes/admin-settings.php';
require_once VC_TABELA_PLUGIN_DIR . 'includes/calculator.php';
require_once VC_TABELA_PLUGIN_DIR . 'includes/ajax-handler.php';

// Register activation hook
register_activation_hook(__FILE__, 'vc_tabela_activate');

function vc_tabela_activate() {
    // Set default options
    $default_options = array(
        'base_prices' => array(
            'pleksi' => 500,
            'krom' => 750,
            'kutu_harf' => 1000
        ),
        'light_multiplier' => 0.25,
        'installation_fee' => 250
    );
    
    add_option('vc_tabela_options', $default_options);
    
    // Register custom post type for offers
    vc_tabela_register_post_type();
    
    flush_rewrite_rules();
}

// Register deactivation hook
register_deactivation_hook(__FILE__, 'vc_tabela_deactivate');

function vc_tabela_deactivate() {
    flush_rewrite_rules();
}

// Register custom post type
function vc_tabela_register_post_type() {
    register_post_type('vc_offer', array(
        'labels' => array(
            'name' => __('Teklifler', 'tabela-hesapla'),
            'singular_name' => __('Teklif', 'tabela-hesapla')
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => false,
        'supports' => array('title')
    ));
}
add_action('init', 'vc_tabela_register_post_type');

// Enqueue scripts and styles
function vc_tabela_enqueue_scripts() {
    wp_enqueue_style('vc-tabela-style', 
        VC_TABELA_PLUGIN_URL . 'assets/css/style.css', 
        array(), 
        VC_TABELA_VERSION
    );
    
    wp_enqueue_script('vc-tabela-script', 
        VC_TABELA_PLUGIN_URL . 'assets/js/script.js',
        array('jquery'),
        VC_TABELA_VERSION,
        true
    );
    
    wp_localize_script('vc-tabela-script', 'vcTabela', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('vc_tabela_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'vc_tabela_enqueue_scripts');

// Register shortcode
function vc_tabela_shortcode($atts) {
    ob_start();
    include VC_TABELA_PLUGIN_DIR . 'templates/frontend.php';
    return ob_get_clean();
}
add_shortcode('tabela_hesapla', 'vc_tabela_shortcode');
