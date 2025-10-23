<?php
/**
 * Frontend Calculator Template
 * Displays the calculator form on the frontend
 */

if (!defined('ABSPATH')) {
    exit;
}

$types = VC_Tabela_Calculator::get_types();
?>

<div class="vc-tabela-calculator">
    <div class="vc-tabela-form-wrapper">
        <h2 class="vc-tabela-title"><?php _e('Tabela Fiyat Hesaplama', 'tabela-hesapla'); ?></h2>
        
        <form id="vc-tabela-form" class="vc-tabela-form">
            <div class="vc-tabela-row">
                <div class="vc-tabela-col">
                    <label for="vc-type"><?php _e('Tabela Tipi', 'tabela-hesapla'); ?> *</label>
                    <select id="vc-type" name="type" required>
                        <option value=""><?php _e('Seçiniz...', 'tabela-hesapla'); ?></option>
                        <?php foreach ($types as $value => $label) : ?>
                            <option value="<?php echo esc_attr($value); ?>">
                                <?php echo esc_html($label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="vc-tabela-row">
                <div class="vc-tabela-col">
                    <label for="vc-width"><?php _e('Genişlik (cm)', 'tabela-hesapla'); ?> *</label>
                    <input type="number" id="vc-width" name="width" 
                           min="1" step="1" required 
                           placeholder="<?php esc_attr_e('Örn: 100', 'tabela-hesapla'); ?>">
                </div>
                
                <div class="vc-tabela-col">
                    <label for="vc-height"><?php _e('Yükseklik (cm)', 'tabela-hesapla'); ?> *</label>
                    <input type="number" id="vc-height" name="height" 
                           min="1" step="1" required 
                           placeholder="<?php esc_attr_e('Örn: 50', 'tabela-hesapla'); ?>">
                </div>
            </div>
            
            <div class="vc-tabela-row">
                <div class="vc-tabela-col">
                    <label class="vc-tabela-checkbox">
                        <input type="checkbox" id="vc-light" name="has_light" value="1">
                        <span><?php _e('Işıklı Tabela', 'tabela-hesapla'); ?></span>
                    </label>
                </div>
                
                <div class="vc-tabela-col">
                    <label class="vc-tabela-checkbox">
                        <input type="checkbox" id="vc-installation" name="has_installation" value="1">
                        <span><?php _e('Montaj Dahil', 'tabela-hesapla'); ?></span>
                    </label>
                </div>
            </div>
            
            <div class="vc-tabela-result" id="vc-result" style="display:none;">
                <div class="vc-tabela-result-item">
                    <span class="label"><?php _e('Alan:', 'tabela-hesapla'); ?></span>
                    <span class="value" id="vc-result-area">-</span> m²
                </div>
                <div class="vc-tabela-result-item">
                    <span class="label"><?php _e('Birim Fiyat:', 'tabela-hesapla'); ?></span>
                    <span class="value" id="vc-result-base">-</span> TL/m²
                </div>
                <div class="vc-tabela-result-item">
                    <span class="label"><?php _e('Ara Toplam:', 'tabela-hesapla'); ?></span>
                    <span class="value" id="vc-result-subtotal">-</span> TL
                </div>
                <div class="vc-tabela-result-item" id="vc-result-light-row" style="display:none;">
                    <span class="label"><?php _e('Işık Maliyeti:', 'tabela-hesapla'); ?></span>
                    <span class="value" id="vc-result-light">-</span> TL
                </div>
                <div class="vc-tabela-result-item" id="vc-result-installation-row" style="display:none;">
                    <span class="label"><?php _e('Montaj Ücreti:', 'tabela-hesapla'); ?></span>
                    <span class="value" id="vc-result-installation">-</span> TL
                </div>
                <div class="vc-tabela-result-total">
                    <span class="label"><?php _e('Toplam Fiyat:', 'tabela-hesapla'); ?></span>
                    <span class="value" id="vc-result-total">-</span> TL
                </div>
            </div>
            
            <div class="vc-tabela-contact-info" id="vc-contact-form" style="display:none;">
                <h3><?php _e('İletişim Bilgileriniz', 'tabela-hesapla'); ?></h3>
                
                <div class="vc-tabela-row">
                    <div class="vc-tabela-col">
                        <label for="vc-name"><?php _e('Ad Soyad', 'tabela-hesapla'); ?> *</label>
                        <input type="text" id="vc-name" name="name" required>
                    </div>
                </div>
                
                <div class="vc-tabela-row">
                    <div class="vc-tabela-col">
                        <label for="vc-email"><?php _e('E-posta', 'tabela-hesapla'); ?> *</label>
                        <input type="email" id="vc-email" name="email" required>
                    </div>
                    
                    <div class="vc-tabela-col">
                        <label for="vc-phone"><?php _e('Telefon', 'tabela-hesapla'); ?></label>
                        <input type="tel" id="vc-phone" name="phone">
                    </div>
                </div>
                
                <div class="vc-tabela-row">
                    <div class="vc-tabela-col">
                        <label for="vc-message"><?php _e('Mesajınız', 'tabela-hesapla'); ?></label>
                        <textarea id="vc-message" name="message" rows="4"></textarea>
                    </div>
                </div>
                
                <button type="submit" class="vc-tabela-submit">
                    <?php _e('Teklif Gönder', 'tabela-hesapla'); ?>
                </button>
            </div>
            
            <div class="vc-tabela-message" id="vc-message" style="display:none;"></div>
        </form>
    </div>
</div>
