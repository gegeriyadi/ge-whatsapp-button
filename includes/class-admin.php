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
        add_action('wp_ajax_ge_generate_link', array($this, 'ajax_generate_link'));
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
            'default_message' => array(
                'title' => __('Default Message', 'ge-whatsapp-button'),
                'type' => 'textarea'
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
        $sanitized['default_message'] = sanitize_textarea_field($input['default_message']);
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
            
            <div class="ge-link-generator">
                <h2><?php _e('Link Generator Tool', 'ge-whatsapp-button'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Custom Message', 'ge-whatsapp-button'); ?></th>
                        <td>
                            <input type="text" id="ge-custom-message" class="regular-text" placeholder="<?php esc_attr_e('Enter custom message', 'ge-whatsapp-button'); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"></th>
                        <td>
                            <button type="button" id="ge-generate-link" class="button"><?php _e('Generate Link', 'ge-whatsapp-button'); ?></button>
                        </td>
                    </tr>
                </table>
                
                <div id="ge-generated-links" style="display: none;">
                    <h3><?php _e('Generated Links', 'ge-whatsapp-button'); ?></h3>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th><?php _e('Type', 'ge-whatsapp-button'); ?></th>
                                <th><?php _e('Code', 'ge-whatsapp-button'); ?></th>
                                <th><?php _e('Action', 'ge-whatsapp-button'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php _e('Direct Link', 'ge-whatsapp-button'); ?></td>
                                <td><code id="ge-direct-link"></code></td>
                                <td><button type="button" class="button-small ge-copy-btn" data-target="ge-direct-link"><?php _e('Copy', 'ge-whatsapp-button'); ?></button></td>
                            </tr>
                            <tr>
                                <td><?php _e('Shortcode', 'ge-whatsapp-button'); ?></td>
                                <td><code id="ge-shortcode"></code></td>
                                <td><button type="button" class="button-small ge-copy-btn" data-target="ge-shortcode"><?php _e('Copy', 'ge-whatsapp-button'); ?></button></td>
                            </tr>
                            <tr>
                                <td><?php _e('HTML', 'ge-whatsapp-button'); ?></td>
                                <td><code id="ge-html-code"></code></td>
                                <td><button type="button" class="button-small ge-copy-btn" data-target="ge-html-code"><?php _e('Copy', 'ge-whatsapp-button'); ?></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <style>
        .ge-link-generator {
            margin-top: 30px;
            padding: 20px;
            background: #fff;
            border: 1px solid #ccd0d4;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
        }
        .ge-link-generator h2 {
            margin-top: 0;
        }
        #ge-generated-links {
            margin-top: 20px;
        }
        #ge-generated-links code {
            background: #f1f1f1;
            padding: 5px;
            border-radius: 3px;
            font-size: 12px;
            word-break: break-all;
        }
        .ge-copy-btn {
            margin-left: 10px;
        }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            $('#ge-generate-link').on('click', function() {
                var message = $('#ge-custom-message').val();
                if (!message) {
                    alert('<?php _e('Please enter a custom message', 'ge-whatsapp-button'); ?>');
                    return;
                }
                
                $.post(ajaxurl, {
                    action: 'ge_generate_link',
                    message: message,
                    nonce: '<?php echo wp_create_nonce('ge_generate_link'); ?>'
                }, function(response) {
                    if (response.success) {
                        $('#ge-direct-link').text(response.data.direct_link);
                        $('#ge-shortcode').text(response.data.shortcode);
                        $('#ge-html-code').text(response.data.html);
                        $('#ge-generated-links').show();
                    }
                });
            });
            
            $('.ge-copy-btn').on('click', function() {
                var target = $(this).data('target');
                var text = $('#' + target).text();
                navigator.clipboard.writeText(text).then(function() {
                    alert('<?php _e('Copied to clipboard!', 'ge-whatsapp-button'); ?>');
                });
            });
        });
        </script>
        <?php
    }
    
    /**
     * AJAX generate link
     */
    public function ajax_generate_link() {
        if (!wp_verify_nonce($_POST['nonce'], 'ge_generate_link') || !current_user_can('manage_options')) {
            wp_die('Security check failed');
        }
        
        $message = sanitize_text_field($_POST['message']);
        $options = get_option('ge_whatsapp_button_options', array());
        $rotator_url = isset($options['rotator_url']) ? $options['rotator_url'] : '';
        
        if (empty($rotator_url)) {
            wp_send_json_error('Rotator URL not configured');
        }
        
        $direct_link = $rotator_url . '?message=' . urlencode($message);
        $shortcode = '[ge_whatsapp_button message="' . esc_attr($message) . '"]';
        $html = '<a href="' . esc_url($direct_link) . '" class="ge-wa-custom-link" target="_blank">Chat WhatsApp</a>';
        
        wp_send_json_success(array(
            'direct_link' => $direct_link,
            'shortcode' => $shortcode,
            'html' => $html
        ));
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