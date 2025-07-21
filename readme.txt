=== GE WhatsApp Button ===
Contributors: yourname
Tags: whatsapp, chat, button, floating, contact, woocommerce
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 1.0.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Simple floating WhatsApp button that connects to external WhatsApp rotator service with customizable messages and styling.

== Description ==

GE WhatsApp Button is a lightweight WordPress plugin that adds a beautiful floating WhatsApp button to your website. The plugin connects to your external WhatsApp rotator service, allowing you to manage multiple WhatsApp numbers and routing logic externally.

= Key Features =

* **Floating WhatsApp Button** - Automatically displays on all pages with customizable positioning
* **Shortcode Support** - Use `[ge_whatsapp_button]` with various attributes for flexible placement
* **Widget Support** - Add WhatsApp button to sidebars and widget areas
* **WooCommerce Integration** - Dynamic product-specific messages on product pages
* **Link Generator Tool** - Generate direct links, shortcodes, and HTML code from admin
* **Customizable Styling** - Multiple sizes, positions, and CSS customization options
* **Responsive Design** - Works perfectly on all devices with smooth animations
* **Translation Ready** - Fully translatable with .pot file included

= Shortcode Usage =

Basic usage:
`[ge_whatsapp_button]`

With custom message:
`[ge_whatsapp_button message="I'm interested in your services"]`

Inline button with custom text:
`[ge_whatsapp_button message="Contact us now" text="Chat Now" style="inline"]`

With custom size:
`[ge_whatsapp_button message="Get support" size="large"]`

= Configuration =

1. Go to **Settings > GE WhatsApp Button** in your WordPress admin
2. Enter your **Rotator Service URL** (e.g., `https://yourdomain.com/redirect`)
3. Configure button position, size, and appearance
4. Set default message and page display options
5. Save settings and test the button

= Requirements =

* WordPress 5.0 or higher
* PHP 7.4 or higher
* External WhatsApp rotator service URL

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/ge-whatsapp-button` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Go to Settings > GE WhatsApp Button to configure the plugin.
4. Enter your rotator service URL and configure your preferred settings.
5. The floating button will automatically appear on your website.

== Frequently Asked Questions ==

= What is a WhatsApp rotator service? =

A WhatsApp rotator service is an external service that manages multiple WhatsApp numbers and distributes incoming chats among them. This plugin sends users to your rotator service, which then redirects them to the appropriate WhatsApp number.

= Can I use this without a rotator service? =

This plugin is specifically designed to work with external rotator services. If you want to link directly to a single WhatsApp number, you might need a different solution.

= How do I customize the button appearance? =

You can customize the button through the admin settings page. Options include size (small, medium, large), position (bottom-left, bottom-right, custom), animations, and custom CSS.

= Does it work with WooCommerce? =

Yes! The plugin includes WooCommerce integration that automatically includes product names in messages when users click the button on product pages.

= Can I place the button in specific locations? =

Yes, you can use the shortcode `[ge_whatsapp_button]` to place buttons anywhere, or use the widget to add buttons to sidebars.

= Is the plugin mobile-friendly? =

Absolutely! The plugin is fully responsive and includes touch optimizations for mobile devices.

== Screenshots ==

1. Plugin settings page with all configuration options
2. Floating WhatsApp button on website frontend
3. Link generator tool for creating custom links
4. Widget configuration in WordPress admin
5. Shortcode examples and usage
6. WooCommerce product page integration

== Changelog ==

= 1.0.0 =
* Initial release
* Floating WhatsApp button with customizable positioning
* Shortcode support with multiple attributes
* WordPress widget for sidebar placement
* WooCommerce integration
* Link generator tool
* Translation ready
* Responsive design with animations
* Custom CSS support

== Upgrade Notice ==

= 1.0.0 =
Initial release of GE WhatsApp Button plugin.

== Additional Info ==

= Support =

For support and feature requests, please visit our support forum or contact us through our website.

= Contributing =

This plugin is developed with security and performance in mind. All user inputs are properly sanitized and validated.

= Privacy =

This plugin does not collect or store any personal user data. It simply redirects users to your configured WhatsApp rotator service.