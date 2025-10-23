<?php
/**
 * Calculator Class
 * Handles all price calculations for tabela (sign board) pricing
 */

if (!defined('ABSPATH')) {
    exit;
}

class VC_Tabela_Calculator {
    
    /**
     * Calculate total price based on dimensions and options
     *
     * @param array $data Calculation data
     * @return array Calculation results
     */
    public static function calculate($data) {
        $options = get_option('vc_tabela_options');
        
        // Sanitize input
        $width = floatval($data['width']);
        $height = floatval($data['height']);
        $type = sanitize_text_field($data['type']);
        $has_light = !empty($data['has_light']);
        $has_installation = !empty($data['has_installation']);
        
        // Validate dimensions
        if ($width <= 0 || $height <= 0) {
            return array(
                'success' => false,
                'message' => __('Lütfen geçerli boyutlar girin', 'tabela-hesapla')
            );
        }
        
        // Calculate area in square meters
        $area = ($width / 100) * ($height / 100);
        
        // Get base price for selected type
        $base_price = isset($options['base_prices'][$type]) ? 
            floatval($options['base_prices'][$type]) : 0;
        
        if ($base_price <= 0) {
            return array(
                'success' => false,
                'message' => __('Geçersiz tabela tipi', 'tabela-hesapla')
            );
        }
        
        // Calculate base total
        $subtotal = $base_price * $area;
        
        // Add light cost if selected
        $light_cost = 0;
        if ($has_light) {
            $light_multiplier = floatval($options['light_multiplier']);
            $light_cost = $subtotal * $light_multiplier;
        }
        
        // Add installation fee if selected
        $installation_cost = 0;
        if ($has_installation) {
            $installation_cost = floatval($options['installation_fee']);
        }
        
        // Calculate total
        $total = $subtotal + $light_cost + $installation_cost;
        
        return array(
            'success' => true,
            'area' => number_format($area, 2),
            'base_price' => number_format($base_price, 2),
            'subtotal' => number_format($subtotal, 2),
            'light_cost' => number_format($light_cost, 2),
            'installation_cost' => number_format($installation_cost, 2),
            'total' => number_format($total, 2)
        );
    }
    
    /**
     * Get available tabela types
     *
     * @return array Available types
     */
    public static function get_types() {
        return array(
            'pleksi' => __('Pleksi Tabela', 'tabela-hesapla'),
            'krom' => __('Krom Tabela', 'tabela-hesapla'),
            'kutu_harf' => __('Kutu Harf', 'tabela-hesapla')
        );
    }
}
