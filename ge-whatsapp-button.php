<?php
/**
 * Plugin Name: GE WhatsApp Button
 * Plugin URI: https://gegeriyadi.com/ge-whatsapp-button
 * Description: Simple floating WhatsApp button that connects to external WhatsApp rotator service with customizable messages and styling.
 * Version: 1.0.0
 * Author: Gege Riyadi
 * Text Domain: ge-whatsapp-button
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('GE_WHATSAPP_BUTTON_VERSION', '1.0.0');
define('GE_WHATSAPP_BUTTON_PLUGIN_URL', plugin_dir_url(__FILE__));
define('GE_WHATSAPP_BUTTON_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('GE_WHATSAPP_BUTTON_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main plugin class
 */
class GE_WhatsApp_Button {
    
    /**
     * Instance
     */
    private static $instance = null;
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        add_action('init', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    /**
     * Initialize plugin
     */
    public function init() {
        // Load text domain
        load_plugin_textdomain('ge-whatsapp-button', false, dirname(plugin_basename(__FILE__)) . '/languages');
        
        // Include required files
        $this->includes();
        
        // Initialize admin
        if (is_admin()) {
            new GE_WhatsApp_Button_Admin();
        }
        
        // Initialize frontend
        if (!is_admin()) {
            new GE_WhatsApp_Button_Frontend();
        }
        
        // Initialize widget
        add_action('widgets_init', array($this, 'register_widget'));
        
        // Add shortcode
        add_shortcode('ge_whatsapp_button', array($this, 'shortcode'));
    }
    
    /**
     * Include required files
     */
    private function includes() {
        require_once GE_WHATSAPP_BUTTON_PLUGIN_PATH . 'includes/class-admin.php';
        require_once GE_WHATSAPP_BUTTON_PLUGIN_PATH . 'includes/class-frontend.php';
        require_once GE_WHATSAPP_BUTTON_PLUGIN_PATH . 'includes/class-widget.php';
    }
    
    /**
     * Register widget
     */
    public function register_widget() {
        register_widget('GE_WhatsApp_Button_Widget');
    }
    
    /**
     * Shortcode handler
     */
    public function shortcode($atts, $content = '') {
        $atts = shortcode_atts(array(
            'message' => '',
            'text' => __('Chat WhatsApp', 'ge-whatsapp-button'),
            'style' => 'floating',
            'size' => '',
            'url' => '',
            'class' => ''
        ), $atts, 'ge_whatsapp_button');
        
        $options = get_option('ge_whatsapp_button_options', array());
        
        // Get values
        $rotator_url = !empty($atts['url']) ? $atts['url'] : (isset($options['rotator_url']) ? $options['rotator_url'] : '');
        $message = $atts['message']; // Only use message from shortcode attribute
        $size_class = !empty($atts['size']) ? 'ge-wa-' . sanitize_html_class($atts['size']) : '';
        $style_class = 'ge-wa-' . sanitize_html_class($atts['style']);
        $custom_classes = !empty($atts['class']) ? ' ' . sanitize_html_class($atts['class']) : '';
        
        if (empty($rotator_url)) {
            return '';
        }
        
        $onclick = sprintf(
            "geRedirectToWhatsApp('%s'); return false;",
            esc_js($message)
        );
        
        if ($atts['style'] === 'inline') {
            return sprintf(
                '<a href="#" class="ge-wa-button ge-wa-inline %s%s" onclick="%s">%s</a>',
                $size_class,
                $custom_classes,
                $onclick,
                esc_html($atts['text'])
            );
        } else {
            return sprintf(
                '<div class="ge-whatsapp-float %s%s">
                    <a href="#" class="ge-wa-icon" onclick="%s" title="%s">
                        <img src="data:image/svg+xml;charset=utf-8,%%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\'%%3E%%3Cpath fill=\'%%23fff\' d=\'M3.516 3.516c4.686-4.686 12.284-4.686 16.97 0s4.686 12.283 0 16.97a12 12 0 0 1-13.754 2.299l-5.814.735a.392.392 0 0 1-.438-.44l.748-5.788A12 12 0 0 1 3.517 3.517zm3.61 17.043.3.158a9.85 9.85 0 0 0 11.534-1.758c3.843-3.843 3.843-10.074 0-13.918s-10.075-3.843-13.918 0a9.85 9.85 0 0 0-1.747 11.554l.16.303-.51 3.942a.196.196 0 0 0 .219.22zm6.534-7.003-.933 1.164a9.84 9.84 0 0 1-3.497-3.495l1.166-.933a.79.79 0 0 0 .23-.94L9.561 6.96a.79.79 0 0 0-.924-.445l-2.023.524a.797.797 0 0 0-.588.88 11.754 11.754 0 0 0 10.005 10.005.797.797 0 0 0 .88-.587l.525-2.023a.79.79 0 0 0-.445-.923L14.6 13.327a.79.79 0 0 0-.94.23z\'%%3E%%3C/svg%%3E" alt="WhatsApp" />
                    </a>
                </div>',
                $size_class,
                $custom_classes,
                $onclick,
                esc_attr($atts['text'])
            );
        }
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        // Set default options
        $default_options = array(
            'enabled' => true,
            'rotator_url' => '',
            'position' => 'bottom-right',
            'custom_bottom' => 20,
            'custom_right' => 20,
            'button_size' => 'medium',
            'show_on_homepage' => true,
            'show_on_posts' => true,
            'show_on_pages' => true,
            'show_on_shop' => true,
            'show_on_products' => true,
            'hide_on_pages' => '',
            'enable_animations' => true,
            'custom_css' => ''
        );
        
        add_option('ge_whatsapp_button_options', $default_options);
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Clean up if needed
    }
}

// Initialize plugin
GE_WhatsApp_Button::get_instance();
