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

// Add meta box for offer details
function vc_tabela_add_meta_boxes() {
    add_meta_box(
        'vc_offer_details',
        __('Teklif Detayları', 'tabela-hesapla'),
        'vc_tabela_render_meta_box',
        'vc_offer',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'vc_tabela_add_meta_boxes');

// Render meta box content
function vc_tabela_render_meta_box($post) {
    $customer_name = get_post_meta($post->ID, '_vc_customer_name', true);
    $customer_email = get_post_meta($post->ID, '_vc_customer_email', true);
    $customer_phone = get_post_meta($post->ID, '_vc_customer_phone', true);
    $width = get_post_meta($post->ID, '_vc_width', true);
    $height = get_post_meta($post->ID, '_vc_height', true);
    $type = get_post_meta($post->ID, '_vc_type', true);
    $has_light = get_post_meta($post->ID, '_vc_has_light', true);
    $has_installation = get_post_meta($post->ID, '_vc_has_installation', true);
    $total = get_post_meta($post->ID, '_vc_total', true);
    $message = get_post_meta($post->ID, '_vc_message', true);
    
    $types = VC_Tabela_Calculator::get_types();
    $type_label = isset($types[$type]) ? $types[$type] : $type;
    ?>
    <div class="vc-offer-meta-box">
        <div class="meta-row">
            <div class="meta-label"><?php _e('Müşteri Adı:', 'tabela-hesapla'); ?></div>
            <div class="meta-value"><?php echo esc_html($customer_name); ?></div>
        </div>
        <div class="meta-row">
            <div class="meta-label"><?php _e('E-posta:', 'tabela-hesapla'); ?></div>
            <div class="meta-value">
                <a href="mailto:<?php echo esc_attr($customer_email); ?>">
                    <?php echo esc_html($customer_email); ?>
                </a>
            </div>
        </div>
        <?php if ($customer_phone) : ?>
        <div class="meta-row">
            <div class="meta-label"><?php _e('Telefon:', 'tabela-hesapla'); ?></div>
            <div class="meta-value">
                <a href="tel:<?php echo esc_attr($customer_phone); ?>">
                    <?php echo esc_html($customer_phone); ?>
                </a>
            </div>
        </div>
        <?php endif; ?>
        <div class="meta-row">
            <div class="meta-label"><?php _e('Tabela Tipi:', 'tabela-hesapla'); ?></div>
            <div class="meta-value"><?php echo esc_html($type_label); ?></div>
        </div>
        <div class="meta-row">
            <div class="meta-label"><?php _e('Boyutlar:', 'tabela-hesapla'); ?></div>
            <div class="meta-value">
                <?php echo esc_html($width); ?> cm x <?php echo esc_html($height); ?> cm
            </div>
        </div>
        <div class="meta-row">
            <div class="meta-label"><?php _e('Işıklı:', 'tabela-hesapla'); ?></div>
            <div class="meta-value">
                <?php echo $has_light === 'yes' ? __('Evet', 'tabela-hesapla') : __('Hayır', 'tabela-hesapla'); ?>
            </div>
        </div>
        <div class="meta-row">
            <div class="meta-label"><?php _e('Montaj:', 'tabela-hesapla'); ?></div>
            <div class="meta-value">
                <?php echo $has_installation === 'yes' ? __('Evet', 'tabela-hesapla') : __('Hayır', 'tabela-hesapla'); ?>
            </div>
        </div>
        <div class="meta-row">
            <div class="meta-label"><?php _e('Toplam Fiyat:', 'tabela-hesapla'); ?></div>
            <div class="meta-value"><strong><?php echo esc_html($total); ?> TL</strong></div>
        </div>
        <?php if ($message) : ?>
        <div class="meta-row">
            <div class="meta-label"><?php _e('Mesaj:', 'tabela-hesapla'); ?></div>
            <div class="meta-value"><?php echo nl2br(esc_html($message)); ?></div>
        </div>
        <?php endif; ?>
    </div>
    <?php
}