<?php
/**
 * Widget functionality
 */

if (!defined('ABSPATH')) {
    exit;
}

class GE_WhatsApp_Button_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'ge_whatsapp_button_widget',
            __('GE WhatsApp Button Widget', 'ge-whatsapp-button'),
            array(
                'description' => __('Display WhatsApp button in sidebar', 'ge-whatsapp-button')
            )
        );
    }
    
    /**
     * Widget output
     */
    public function widget($args, $instance) {
        $options = get_option('ge_whatsapp_button_options', array());
        
        // Check if plugin is enabled and rotator URL is set
        if (empty($options['enabled']) || empty($options['rotator_url'])) {
            return;
        }
        
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $message = !empty($instance['message']) ? $instance['message'] : '';
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : __('Chat WhatsApp', 'ge-whatsapp-button');
        $size = !empty($instance['size']) ? $instance['size'] : 'medium';
        
        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
        }
        
        $onclick = sprintf(
            "geRedirectToWhatsApp('%s'); return false;",
            esc_js($message)
        );
        
        printf(
            '<div class="ge-wa-widget">
                <button class="ge-wa-button ge-wa-widget-btn ge-wa-%s" onclick="%s" type="button">
                    <span class="ge-wa-widget-icon"></span>
                    <span>%s</span>
                </button>
            </div>',
            esc_attr($size),
            $onclick,
            esc_html($button_text)
        );
        
        echo $args['after_widget'];
    }
    
    /**
     * Widget form
     */
    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : '';
        $message = isset($instance['message']) ? $instance['message'] : '';
        $button_text = isset($instance['button_text']) ? $instance['button_text'] : __('Chat WhatsApp', 'ge-whatsapp-button');
        $size = isset($instance['size']) ? $instance['size'] : 'medium';
        ?>
        
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'ge-whatsapp-button'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('message'); ?>"><?php _e('Custom Message:', 'ge-whatsapp-button'); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('message'); ?>" name="<?php echo $this->get_field_name('message'); ?>" rows="3"><?php echo esc_textarea($message); ?></textarea>
            <small><?php _e('Custom message for this widget instance', 'ge-whatsapp-button'); ?></small>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('button_text'); ?>"><?php _e('Button Text:', 'ge-whatsapp-button'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('button_text'); ?>" name="<?php echo $this->get_field_name('button_text'); ?>" type="text" value="<?php echo esc_attr($button_text); ?>" />
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Button Size:', 'ge-whatsapp-button'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('size'); ?>" name="<?php echo $this->get_field_name('size'); ?>">
                <option value="small" <?php selected($size, 'small'); ?>><?php _e('Small', 'ge-whatsapp-button'); ?></option>
                <option value="medium" <?php selected($size, 'medium'); ?>><?php _e('Medium', 'ge-whatsapp-button'); ?></option>
                <option value="large" <?php selected($size, 'large'); ?>><?php _e('Large', 'ge-whatsapp-button'); ?></option>
            </select>
        </p>
        
        <?php
    }
    
    /**
     * Update widget
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        $instance['message'] = !empty($new_instance['message']) ? sanitize_textarea_field($new_instance['message']) : '';
        $instance['button_text'] = !empty($new_instance['button_text']) ? sanitize_text_field($new_instance['button_text']) : '';
        $instance['size'] = !empty($new_instance['size']) ? sanitize_text_field($new_instance['size']) : 'medium';
        
        return $instance;
    }
}