<?php
if (!defined('ABSPATH')) {
    exit;
}

// Add admin menu
function vc_tabela_admin_menu() {
    add_menu_page(
        __('Tabela Hesaplama', 'tabela-hesapla'),
        __('Tabela Hesaplama', 'tabela-hesapla'),
        'manage_options',
        'tabela-hesapla',
        'vc_tabela_settings_page',
        'dashicons-calculator',
        30
    );
    
    add_submenu_page(
        'tabela-hesapla',
        __('Teklifler', 'tabela-hesapla'),
        __('Teklifler', 'tabela-hesapla'),
        'manage_options',
        'edit.php?post_type=vc_offer'
    );
}
add_action('admin_menu', 'vc_tabela_admin_menu');

// Register settings
function vc_tabela_register_settings() {
    register_setting('vc_tabela_options', 'vc_tabela_options');
}
add_action('admin_init', 'vc_tabela_register_settings');

// Settings page content
function vc_tabela_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $options = get_option('vc_tabela_options');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <form method="post" action="options.php">
            <?php settings_fields('vc_tabela_options'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Pleksi Tabela (m² fiyatı)', 'tabela-hesapla'); ?></th>
                    <td>
                        <input type="number" name="vc_tabela_options[base_prices][pleksi]" 
                               value="<?php echo esc_attr($options['base_prices']['pleksi']); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Krom Tabela (m² fiyatı)', 'tabela-hesapla'); ?></th>
                    <td>
                        <input type="number" name="vc_tabela_options[base_prices][krom]" 
                               value="<?php echo esc_attr($options['base_prices']['krom']); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Kutu Harf (m² fiyatı)', 'tabela-hesapla'); ?></th>
                    <td>
                        <input type="number" name="vc_tabela_options[base_prices][kutu_harf]" 
                               value="<?php echo esc_attr($options['base_prices']['kutu_harf']); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Işıklı Tabela Çarpanı', 'tabela-hesapla'); ?></th>
                    <td>
                        <input type="number" step="0.01" name="vc_tabela_options[light_multiplier]" 
                               value="<?php echo esc_attr($options['light_multiplier']); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Montaj Ücreti', 'tabela-hesapla'); ?></th>
                    <td>
                        <input type="number" name="vc_tabela_options[installation_fee]" 
                               value="<?php echo esc_attr($options['installation_fee']); ?>">
                    </td>
                </tr>
            </table>
            
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}