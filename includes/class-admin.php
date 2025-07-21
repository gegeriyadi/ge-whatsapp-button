<?php
/**
 * Admin functionality
 */

if (!defined('ABSPATH')) {
    exit;
}

class GE_WhatsApp_Button_Admin {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'init_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_options_page(
            __('GE WhatsApp Button Settings', 'ge-whatsapp-button'),
            __('GE WhatsApp Button', 'ge-whatsapp-button'),
            'manage_options',
            'ge-whatsapp-button',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Initialize settings
     */
    public function init_settings() {
        register_setting(
            'ge_whatsapp_button_settings',
            'ge_whatsapp_button_options',
            array($this, 'sanitize_options')
        );
        
        // Main settings section
        add_settings_section(
            'ge_whatsapp_button_main',
            __('Main Settings', 'ge-whatsapp-button'),
            null,
            'ge-whatsapp-button'
        );
        
        // Fields
        $fields = array(
            'enabled' => array(
                'title' => __('Enable Plugin', 'ge-whatsapp-button'),
                'type' => 'checkbox'
            ),
            'rotator_url' => array(
                'title' => __('Rotator Service URL', 'ge-whatsapp-button'),
                'type' => 'url'
            ),
            'position' => array(
                'title' => __('Button Position', 'ge-whatsapp-button'),
                'type' => 'select',
                'options' => array(
                    'bottom-left' => __('Bottom Left', 'ge-whatsapp-button'),
                    'bottom-right' => __('Bottom Right', 'ge-whatsapp-button'),
                    'custom' => __('Custom Position', 'ge-whatsapp-button')
                )
            ),
            'custom_bottom' => array(
                'title' => __('Custom Bottom Position (px)', 'ge-whatsapp-button'),
                'type' => 'number'
            ),
            'custom_right' => array(
                'title' => __('Custom Right Position (px)', 'ge-whatsapp-button'),
                'type' => 'number'
            ),
            'button_size' => array(
                'title' => __('Button Size', 'ge-whatsapp-button'),
                'type' => 'select',
                'options' => array(
                    'small' => __('Small (50px)', 'ge-whatsapp-button'),
                    'medium' => __('Medium (65px)', 'ge-whatsapp-button'),
                    'large' => __('Large (80px)', 'ge-whatsapp-button')
                )
            ),
            'show_on_homepage' => array(
                'title' => __('Show on Homepage', 'ge-whatsapp-button'),
                'type' => 'checkbox'
            ),
            'show_on_posts' => array(
                'title' => __('Show on Posts', 'ge-whatsapp-button'),
                'type' => 'checkbox'
            ),
            'show_on_pages' => array(
                'title' => __('Show on Pages', 'ge-whatsapp-button'),
                'type' => 'checkbox'
            ),
            'show_on_shop' => array(
                'title' => __('Show on Shop Page', 'ge-whatsapp-button'),
                'type' => 'checkbox'
            ),
            'show_on_products' => array(
                'title' => __('Show on Product Pages', 'ge-whatsapp-button'),
                'type' => 'checkbox'
            ),
            'hide_on_pages' => array(
                'title' => __('Hide on Specific Pages (comma separated IDs)', 'ge-whatsapp-button'),
                'type' => 'text'
            ),
            'enable_animations' => array(
                'title' => __('Enable Animations', 'ge-whatsapp-button'),
                'type' => 'checkbox'
            ),
            'custom_css' => array(
                'title' => __('Custom CSS', 'ge-whatsapp-button'),
                'type' => 'textarea'
            )
        );
        
        foreach ($fields as $field_id => $field) {
            add_settings_field(
                $field_id,
                $field['title'],
                array($this, 'render_field'),
                'ge-whatsapp-button',
                'ge_whatsapp_button_main',
                array('field_id' => $field_id, 'field' => $field)
            );
        }
    }
    
    /**
     * Render field
     */
    public function render_field($args) {
        $field_id = $args['field_id'];
        $field = $args['field'];
        $options = get_option('ge_whatsapp_button_options', array());
        $value = isset($options[$field_id]) ? $options[$field_id] : '';
        
        switch ($field['type']) {
            case 'checkbox':
                printf(
                    '<input type="checkbox" id="%s" name="ge_whatsapp_button_options[%s]" value="1" %s />',
                    esc_attr($field_id),
                    esc_attr($field_id),
                    checked(1, $value, false)
                );
                break;
                
            case 'url':
                printf(
                    '<input type="url" id="%s" name="ge_whatsapp_button_options[%s]" value="%s" class="regular-text" placeholder="https://yourdomain.com/redirect" />',
                    esc_attr($field_id),
                    esc_attr($field_id),
                    esc_attr($value)
                );
                break;
                
            case 'text':
                printf(
                    '<input type="text" id="%s" name="ge_whatsapp_button_options[%s]" value="%s" class="regular-text" />',
                    esc_attr($field_id),
                    esc_attr($field_id),
                    esc_attr($value)
                );
                break;
                
            case 'number':
                printf(
                    '<input type="number" id="%s" name="ge_whatsapp_button_options[%s]" value="%s" min="0" max="500" />',
                    esc_attr($field_id),
                    esc_attr($field_id),
                    esc_attr($value)
                );
                break;
                
            case 'textarea':
                printf(
                    '<textarea id="%s" name="ge_whatsapp_button_options[%s]" rows="4" class="regular-text">%s</textarea>',
                    esc_attr($field_id),
                    esc_attr($field_id),
                    esc_textarea($value)
                );
                break;
                
            case 'select':
                printf('<select id="%s" name="ge_whatsapp_button_options[%s]">', esc_attr($field_id), esc_attr($field_id));
                foreach ($field['options'] as $option_value => $option_label) {
                    printf(
                        '<option value="%s" %s>%s</option>',
                        esc_attr($option_value),
                        selected($value, $option_value, false),
                        esc_html($option_label)
                    );
                }
                echo '</select>';
                break;
        }
    }
    
    /**
     * Sanitize options
     */
    public function sanitize_options($input) {
        $sanitized = array();
        
        $sanitized['enabled'] = isset($input['enabled']) ? 1 : 0;
        $sanitized['rotator_url'] = esc_url_raw($input['rotator_url']);
        $sanitized['position'] = sanitize_text_field($input['position']);
        $sanitized['custom_bottom'] = absint($input['custom_bottom']);
        $sanitized['custom_right'] = absint($input['custom_right']);
        $sanitized['button_size'] = sanitize_text_field($input['button_size']);
        $sanitized['show_on_homepage'] = isset($input['show_on_homepage']) ? 1 : 0;
        $sanitized['show_on_posts'] = isset($input['show_on_posts']) ? 1 : 0;
        $sanitized['show_on_pages'] = isset($input['show_on_pages']) ? 1 : 0;
        $sanitized['show_on_shop'] = isset($input['show_on_shop']) ? 1 : 0;
        $sanitized['show_on_products'] = isset($input['show_on_products']) ? 1 : 0;
        $sanitized['hide_on_pages'] = sanitize_text_field($input['hide_on_pages']);
        $sanitized['enable_animations'] = isset($input['enable_animations']) ? 1 : 0;
        $sanitized['custom_css'] = wp_strip_all_tags($input['custom_css']);
        
        return $sanitized;
    }
    
    /**
     * Admin page
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('GE WhatsApp Button Settings', 'ge-whatsapp-button'); ?></h1>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('ge_whatsapp_button_settings');
                do_settings_sections('ge-whatsapp-button');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
    
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'settings_page_ge-whatsapp-button') {
            return;
        }
        
        wp_enqueue_script('jquery');
        wp_enqueue_style(
            'ge-whatsapp-admin',
            GE_WHATSAPP_BUTTON_PLUGIN_URL . 'admin/css/admin-style.css',
            array(),
            GE_WHATSAPP_BUTTON_VERSION
        );
    }
}