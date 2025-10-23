/**
 * Tabela Hesapla JavaScript
 * Handles real-time calculations and form submission
 */

(function($) {
    'use strict';
    
    var VCTabela = {
        
        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
        },
        
        /**
         * Bind event handlers
         */
        bindEvents: function() {
            var self = this;
            
            // Real-time calculation on input change
            $('#vc-tabela-form').on('change keyup', 'input[type="number"], select, input[type="checkbox"]', function() {
                self.calculate();
            });
            
            // Form submission
            $('#vc-tabela-form').on('submit', function(e) {
                e.preventDefault();
                self.submitForm();
            });
        },
        
        /**
         * Calculate price in real-time
         */
        calculate: function() {
            var type = $('#vc-type').val();
            var width = parseFloat($('#vc-width').val()) || 0;
            var height = parseFloat($('#vc-height').val()) || 0;
            var hasLight = $('#vc-light').is(':checked');
            var hasInstallation = $('#vc-installation').is(':checked');
            
            // Validate inputs
            if (!type || width <= 0 || height <= 0) {
                $('#vc-result').hide();
                $('#vc-contact-form').hide();
                return;
            }
            
            // Show loading state
            $('#vc-result').show();
            $('#vc-result .value').text('...');
            
            // Make AJAX request
            $.ajax({
                url: vcTabela.ajaxurl,
                type: 'POST',
                data: {
                    action: 'vc_tabela_calculate',
                    nonce: vcTabela.nonce,
                    type: type,
                    width: width,
                    height: height,
                    has_light: hasLight,
                    has_installation: hasInstallation
                },
                success: function(response) {
                    if (response.success) {
                        VCTabela.displayResult(response);
                        $('#vc-contact-form').slideDown();
                    } else {
                        VCTabela.showMessage(response.message, 'error');
                        $('#vc-result').hide();
                        $('#vc-contact-form').hide();
                    }
                },
                error: function() {
                    VCTabela.showMessage('Hesaplama sırasında bir hata oluştu.', 'error');
                    $('#vc-result').hide();
                    $('#vc-contact-form').hide();
                }
            });
        },
        
        /**
         * Display calculation result
         */
        displayResult: function(response) {
            $('#vc-result-area').text(response.area);
            $('#vc-result-base').text(response.base_price);
            $('#vc-result-subtotal').text(response.subtotal);
            $('#vc-result-total').text(response.total);
            
            // Show/hide light cost
            if (parseFloat(response.light_cost) > 0) {
                $('#vc-result-light').text(response.light_cost);
                $('#vc-result-light-row').show();
            } else {
                $('#vc-result-light-row').hide();
            }
            
            // Show/hide installation cost
            if (parseFloat(response.installation_cost) > 0) {
                $('#vc-result-installation').text(response.installation_cost);
                $('#vc-result-installation-row').show();
            } else {
                $('#vc-result-installation-row').hide();
            }
        },
        
        /**
         * Submit form
         */
        submitForm: function() {
            var self = this;
            
            // Get form data
            var formData = {
                action: 'vc_tabela_submit',
                nonce: vcTabela.nonce,
                name: $('#vc-name').val(),
                email: $('#vc-email').val(),
                phone: $('#vc-phone').val(),
                width: $('#vc-width').val(),
                height: $('#vc-height').val(),
                type: $('#vc-type').val(),
                has_light: $('#vc-light').is(':checked'),
                has_installation: $('#vc-installation').is(':checked'),
                total: $('#vc-result-total').text(),
                message: $('#vc-message').val()
            };
            
            // Disable submit button
            var $submitBtn = $('.vc-tabela-submit');
            $submitBtn.prop('disabled', true).text('Gönderiliyor...');
            
            // Make AJAX request
            $.ajax({
                url: vcTabela.ajaxurl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        self.showMessage(response.data.message, 'success');
                        $('#vc-tabela-form')[0].reset();
                        $('#vc-result').hide();
                        $('#vc-contact-form').hide();
                    } else {
                        self.showMessage(response.data.message, 'error');
                    }
                    $submitBtn.prop('disabled', false).text('Teklif Gönder');
                },
                error: function() {
                    self.showMessage('Form gönderilirken bir hata oluştu.', 'error');
                    $submitBtn.prop('disabled', false).text('Teklif Gönder');
                }
            });
        },
        
        /**
         * Show message
         */
        showMessage: function(message, type) {
            var $messageDiv = $('#vc-message');
            $messageDiv
                .removeClass('success error')
                .addClass(type)
                .html(message)
                .slideDown();
            
            // Auto-hide after 5 seconds
            setTimeout(function() {
                $messageDiv.slideUp();
            }, 5000);
        }
    };
    
    // Initialize on document ready
    $(document).ready(function() {
        VCTabela.init();
    });
    
})(jQuery);
