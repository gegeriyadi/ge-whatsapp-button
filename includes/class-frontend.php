<?php
/**
 * Frontend functionality
 */

if (!defined('ABSPATH')) {
    exit;
}

class GE_WhatsApp_Button_Frontend {
    
    private $options;
    
    public function __construct() {
        $this->options = get_option('ge_whatsapp_button_options', array());
        
        if (!empty($this->options['enabled'])) {
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
            add_action('wp_footer', array($this, 'display_button'));
        }
    }
    
    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Only load on pages where button should show
        if (!$this->should_display_button()) {
            return;
        }
        
        // CSS
        wp_enqueue_style(
            'ge-whatsapp-button',
            GE_WHATSAPP_BUTTON_PLUGIN_URL . 'public/css/public-style.css',
            array(),
            GE_WHATSAPP_BUTTON_VERSION
        );
        
        // Custom CSS
        if (!empty($this->options['custom_css'])) {
            wp_add_inline_style('ge-whatsapp-button', $this->options['custom_css']);
        }
        
        // JavaScript
        wp_enqueue_script(
            'ge-whatsapp-button',
            GE_WHATSAPP_BUTTON_PLUGIN_URL . 'public/js/public-script.js',
            array('jquery'),
            GE_WHATSAPP_BUTTON_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script('ge-whatsapp-button', 'ge_whatsapp_vars', array(
            'rotator_url' => isset($this->options['rotator_url']) ? $this->options['rotator_url'] : '',
            'default_message' => isset($this->options['default_message']) ? $this->options['default_message'] : ''
        ));
    }
    
    /**
     * Display floating button
     */
    public function display_button() {
        if (!$this->should_display_button()) {
            return;
        }
        
        $position_class = $this->get_position_class();
        $size_class = $this->get_size_class();
        $animation_class = !empty($this->options['enable_animations']) ? 'ge-wa-animated' : '';
        $custom_style = $this->get_custom_position_style();
        
        $woocommerce_message = $this->get_woocommerce_message();
        $message = !empty($woocommerce_message) ? $woocommerce_message : (isset($this->options['default_message']) ? $this->options['default_message'] : '');
        
        echo sprintf(
            '<div class="ge-whatsapp-float %s %s %s" style="%s">
                <a href="#" class="ge-wa-icon" onclick="geRedirectToWhatsApp(\'%s\'); return false;" title="%s">
                    <img src="data:image/svg+xml;charset=utf-8,%%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\'%%3E%%3Cpath fill=\'%%23fff\' d=\'M3.516 3.516c4.686-4.686 12.284-4.686 16.97 0s4.686 12.283 0 16.97a12 12 0 0 1-13.754 2.299l-5.814.735a.392.392 0 0 1-.438-.44l.748-5.788A12 12 0 0 1 3.517 3.517zm3.61 17.043.3.158a9.85 9.85 0 0 0 11.534-1.758c3.843-3.843 3.843-10.074 0-13.918s-10.075-3.843-13.918 0a9.85 9.85 0 0 0-1.747 11.554l.16.303-.51 3.942a.196.196 0 0 0 .219.22zm6.534-7.003-.933 1.164a9.84 9.84 0 0 1-3.497-3.495l1.166-.933a.79.79 0 0 0 .23-.94L9.561 6.96a.79.79 0 0 0-.924-.445l-2.023.524a.797.797 0 0 0-.588.88 11.754 11.754 0 0 0 10.005 10.005.797.797 0 0 0 .88-.587l.525-2.023a.79.79 0 0 0-.445-.923L14.6 13.327a.79.79 0 0 0-.94.23z\'%%3E%%3C/svg%%3E" alt="WhatsApp" />
                </a>
            </div>',
            esc_attr($position_class),
            esc_attr($size_class),
            esc_attr($animation_class),
            esc_attr($custom_style),
            esc_js($message),
            esc_attr(__('Chat WhatsApp', 'ge-whatsapp-button'))
        );
    }
    
    /**
     * Check if button should display on current page
     */
    private function should_display_button() {
        // Check if rotator URL is set
        if (empty($this->options['rotator_url'])) {
            return false;
        }
        
        // Check hide on specific pages
        if (!empty($this->options['hide_on_pages'])) {
            $hide_pages = array_map('trim', explode(',', $this->options['hide_on_pages']));
            if (in_array(get_the_ID(), $hide_pages)) {
                return false;
            }
        }
        
        // Check page types
        if (is_front_page() || is_home()) {
            return !empty($this->options['show_on_homepage']);
        } elseif (is_single()) {
            return !empty($this->options['show_on_posts']);
        } elseif (is_page()) {
            return !empty($this->options['show_on_pages']);
        } elseif (function_exists('is_shop') && is_shop()) {
            return !empty($this->options['show_on_shop']);
        } elseif (function_exists('is_product') && is_product()) {
            return !empty($this->options['show_on_products']);
        }
        
        return false;
    }
    
    /**
     * Get position class
     */
    private function get_position_class() {
        $position = isset($this->options['position']) ? $this->options['position'] : 'bottom-right';
        
        switch ($position) {
            case 'bottom-left':
                return 'ge-wa-bottom-left';
            case 'bottom-right':
                return 'ge-wa-bottom-right';
            case 'custom':
                return 'ge-wa-custom';
            default:
                return 'ge-wa-bottom-right';
        }
    }
    
    /**
     * Get size class
     */
    private function get_size_class() {
        $size = isset($this->options['button_size']) ? $this->options['button_size'] : 'medium';
        return 'ge-wa-' . $size;
    }
    
    /**
     * Get custom position style
     */
    private function get_custom_position_style() {
        if (isset($this->options['position']) && $this->options['position'] === 'custom') {
            $bottom = isset($this->options['custom_bottom']) ? intval($this->options['custom_bottom']) : 20;
            $right = isset($this->options['custom_right']) ? intval($this->options['custom_right']) : 20;
            return "bottom: {$bottom}px; right: {$right}px;";
        }
        return '';
    }
    
    /**
     * Get WooCommerce specific message
     */
    private function get_woocommerce_message() {
        if (function_exists('is_product') && is_product()) {
            global $product;
            if ($product) {
                $product_name = $product->get_name();
                return sprintf(__('I\'m interested in %s', 'ge-whatsapp-button'), $product_name);
            }
        }
        return '';
    }
}