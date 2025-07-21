<?php
/**
 * Uninstall script for GE WhatsApp Button
 */

// If uninstall not called from WordPress, then exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options
delete_option('ge_whatsapp_button_options');

// Delete any transients
delete_transient('ge_whatsapp_button_cache');

// Clean up user meta if any (for per-user settings)
$users = get_users();
foreach ($users as $user) {
    delete_user_meta($user->ID, 'ge_whatsapp_button_disabled');
}

// Remove any scheduled events
wp_clear_scheduled_hook('ge_whatsapp_button_cleanup');

// Clear any cached data
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
}