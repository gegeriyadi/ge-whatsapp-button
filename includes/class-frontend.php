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
            'rotator_url' => isset($this->options['rotator_url']) ? $this->options['rotator_url'] : ''
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
        $message = !empty($woocommerce_message) ? $woocommerce_message : '';
        
        echo sprintf(
            '<div class="ge-whatsapp-float %s %s %s" style="%s">
                <a href="#" class="ge-wa-icon" onclick="geRedirectToWhatsApp(\'%s\'); return false;" title="%s" aria-label="%s">
                </a>
            </div>',
            esc_attr($position_class),
            esc_attr($size_class),
            esc_attr($animation_class),
            esc_attr($custom_style),
            esc_js($message),
            esc_attr(__('Chat WhatsApp', 'ge-whatsapp-button')),
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