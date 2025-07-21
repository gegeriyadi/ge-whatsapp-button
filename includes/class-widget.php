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
                <a href="#" class="ge-wa-button ge-wa-widget-btn ge-wa-%s" onclick="%s">
                    <img src="data:image/svg+xml;charset=utf-8,%%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\'%%3E%%3Cpath fill=\'%%23fff\' d=\'M3.516 3.516c4.686-4.686 12.284-4.686 16.97 0s4.686 12.283 0 16.97a12 12 0 0 1-13.754 2.299l-5.814.735a.392.392 0 0 1-.438-.44l.748-5.788A12 12 0 0 1 3.517 3.517zm3.61 17.043.3.158a9.85 9.85 0 0 0 11.534-1.758c3.843-3.843 3.843-10.074 0-13.918s-10.075-3.843-13.918 0a9.85 9.85 0 0 0-1.747 11.554l.16.303-.51 3.942a.196.196 0 0 0 .219.22zm6.534-7.003-.933 1.164a9.84 9.84 0 0 1-3.497-3.495l1.166-.933a.79.79 0 0 0 .23-.94L9.561 6.96a.79.79 0 0 0-.924-.445l-2.023.524a.797.797 0 0 0-.588.88 11.754 11.754 0 0 0 10.005 10.005.797.797 0 0 0 .88-.587l.525-2.023a.79.79 0 0 0-.445-.923L14.6 13.327a.79.79 0 0 0-.94.23z\'%%3E%%3C/svg%%3E" alt="WhatsApp" />
                    <span>%s</span>
                </a>
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