<?php
/**
 * AJAX Handler
 * Handles all AJAX requests for the plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle AJAX calculation request
 */
function vc_tabela_ajax_calculate() {
    // Verify nonce
    check_ajax_referer('vc_tabela_nonce', 'nonce');
    
    // Get posted data
    $data = array(
        'width' => isset($_POST['width']) ? $_POST['width'] : 0,
        'height' => isset($_POST['height']) ? $_POST['height'] : 0,
        'type' => isset($_POST['type']) ? $_POST['type'] : '',
        'has_light' => isset($_POST['has_light']) ? $_POST['has_light'] : false,
        'has_installation' => isset($_POST['has_installation']) ? $_POST['has_installation'] : false
    );
    
    // Calculate
    $result = VC_Tabela_Calculator::calculate($data);
    
    // Return JSON response
    wp_send_json($result);
}
add_action('wp_ajax_vc_tabela_calculate', 'vc_tabela_ajax_calculate');
add_action('wp_ajax_nopriv_vc_tabela_calculate', 'vc_tabela_ajax_calculate');

/**
 * Handle AJAX form submission
 */
function vc_tabela_ajax_submit() {
    // Verify nonce
    check_ajax_referer('vc_tabela_nonce', 'nonce');
    
    // Get posted data
    $data = array(
        'name' => isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '',
        'email' => isset($_POST['email']) ? sanitize_email($_POST['email']) : '',
        'phone' => isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '',
        'width' => isset($_POST['width']) ? floatval($_POST['width']) : 0,
        'height' => isset($_POST['height']) ? floatval($_POST['height']) : 0,
        'type' => isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '',
        'has_light' => isset($_POST['has_light']) ? (bool)$_POST['has_light'] : false,
        'has_installation' => isset($_POST['has_installation']) ? (bool)$_POST['has_installation'] : false,
        'total' => isset($_POST['total']) ? sanitize_text_field($_POST['total']) : '',
        'message' => isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : ''
    );
    
    // Validate required fields
    if (empty($data['name']) || empty($data['email'])) {
        wp_send_json_error(array(
            'message' => __('Lütfen tüm zorunlu alanları doldurun', 'tabela-hesapla')
        ));
    }
    
    // Validate email
    if (!is_email($data['email'])) {
        wp_send_json_error(array(
            'message' => __('Lütfen geçerli bir e-posta adresi girin', 'tabela-hesapla')
        ));
    }
    
    // Create post
    $post_id = wp_insert_post(array(
        'post_type' => 'vc_offer',
        'post_title' => sprintf(
            __('Teklif - %s - %s', 'tabela-hesapla'),
            $data['name'],
            current_time('mysql')
        ),
        'post_status' => 'publish',
        'meta_input' => array(
            '_vc_customer_name' => $data['name'],
            '_vc_customer_email' => $data['email'],
            '_vc_customer_phone' => $data['phone'],
            '_vc_width' => $data['width'],
            '_vc_height' => $data['height'],
            '_vc_type' => $data['type'],
            '_vc_has_light' => $data['has_light'] ? 'yes' : 'no',
            '_vc_has_installation' => $data['has_installation'] ? 'yes' : 'no',
            '_vc_total' => $data['total'],
            '_vc_message' => $data['message']
        )
    ));
    
    if (is_wp_error($post_id)) {
        wp_send_json_error(array(
            'message' => __('Teklif kaydedilemedi. Lütfen tekrar deneyin.', 'tabela-hesapla')
        ));
    }
    
    // Send email notification
    vc_tabela_send_notification($post_id, $data);
    
    wp_send_json_success(array(
        'message' => __('Teklifiniz başarıyla gönderildi. En kısa sürede size dönüş yapacağız.', 'tabela-hesapla')
    ));
}
add_action('wp_ajax_vc_tabela_submit', 'vc_tabela_ajax_submit');
add_action('wp_ajax_nopriv_vc_tabela_submit', 'vc_tabela_ajax_submit');

/**
 * Send email notification
 *
 * @param int $post_id Post ID
 * @param array $data Form data
 */
function vc_tabela_send_notification($post_id, $data) {
    $admin_email = get_option('admin_email');
    
    $subject = sprintf(
        __('Yeni Tabela Teklifi - %s', 'tabela-hesapla'),
        $data['name']
    );
    
    $type_labels = VC_Tabela_Calculator::get_types();
    $type_label = isset($type_labels[$data['type']]) ? $type_labels[$data['type']] : $data['type'];
    
    $message = sprintf(
        __("Yeni bir tabela teklifi alındı:\n\n" .
           "Müşteri Bilgileri:\n" .
           "İsim: %s\n" .
           "E-posta: %s\n" .
           "Telefon: %s\n\n" .
           "Teklif Detayları:\n" .
           "Tabela Tipi: %s\n" .
           "Genişlik: %s cm\n" .
           "Yükseklik: %s cm\n" .
           "Işıklı: %s\n" .
           "Montaj: %s\n" .
           "Toplam Fiyat: %s TL\n\n" .
           "Mesaj:\n%s\n\n" .
           "Teklifi görüntülemek için: %s", 'tabela-hesapla'),
        $data['name'],
        $data['email'],
        $data['phone'],
        $type_label,
        $data['width'],
        $data['height'],
        $data['has_light'] ? __('Evet', 'tabela-hesapla') : __('Hayır', 'tabela-hesapla'),
        $data['has_installation'] ? __('Evet', 'tabela-hesapla') : __('Hayır', 'tabela-hesapla'),
        $data['total'],
        $data['message'],
        admin_url('post.php?post=' . $post_id . '&action=edit')
    );
    
    wp_mail($admin_email, $subject, $message);
    
    // Send confirmation email to customer
    $customer_subject = __('Tabela Teklif Talebiniz Alındı', 'tabela-hesapla');
    $customer_message = sprintf(
        __("Merhaba %s,\n\n" .
           "Tabela teklif talebiniz başarıyla alınmıştır.\n\n" .
           "Teklif Özeti:\n" .
           "Tabela Tipi: %s\n" .
           "Boyutlar: %s x %s cm\n" .
           "Tahmini Fiyat: %s TL\n\n" .
           "En kısa sürede size dönüş yapacağız.\n\n" .
           "Teşekkürler!", 'tabela-hesapla'),
        $data['name'],
        $type_label,
        $data['width'],
        $data['height'],
        $data['total']
    );
    
    wp_mail($data['email'], $customer_subject, $customer_message);
}
