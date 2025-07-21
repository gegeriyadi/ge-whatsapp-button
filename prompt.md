# GE WhatsApp Button WordPress Plugin

Tolong buatkan WordPress plugin sederhana dengan spesifikasi berikut:

## Plugin Information
- **Plugin Name:** GE WhatsApp Button
- **Description:** Simple floating WhatsApp button that connects to external WhatsApp rotator service with customizable messages and styling.
- **Version:** 1.0.0
- **Author:** Your Name
- **Plugin URI:** https://yourwebsite.com/ge-whatsapp-button
- **Text Domain:** ge-whatsapp-button
- **Requires at least:** WordPress 5.0
- **Tested up to:** 6.4
- **Requires PHP:** 7.4

## Core Features

### 1. Floating WhatsApp Button
- **Automatic display** pada semua halaman (dengan option untuk disable per page)
- **Customizable position** (bottom-left, bottom-right, custom coordinates)
- **Responsive design** dengan animation (bounce + pulse effect)
- **Custom SVG icon** menggunakan data URI:
```
data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23fff' d='M3.516 3.516c4.686-4.686 12.284-4.686 16.97 0s4.686 12.283 0 16.97a12 12 0 0 1-13.754 2.299l-5.814.735a.392.392 0 0 1-.438-.44l.748-5.788A12 12 0 0 1 3.517 3.517zm3.61 17.043.3.158a9.85 9.85 0 0 0 11.534-1.758c3.843-3.843 3.843-10.074 0-13.918s-10.075-3.843-13.918 0a9.85 9.85 0 0 0-1.747 11.554l.16.303-.51 3.942a.196.196 0 0 0 .219.22zm6.534-7.003-.933 1.164a9.84 9.84 0 0 1-3.497-3.495l1.166-.933a.79.79 0 0 0 .23-.94L9.561 6.96a.79.79 0 0 0-.924-.445l-2.023.524a.797.797 0 0 0-.588.88 11.754 11.754 0 0 0 10.005 10.005.797.797 0 0 0 .88-.587l.525-2.023a.79.79 0 0 0-.445-.923L14.6 13.327a.79.79 0 0 0-.94.23z'/%3E%3C/svg%3E
```

### 2. Admin Settings Page
Location: **WordPress Admin → Settings → GE WhatsApp Button**

**Settings Fields:**
- **Enable/Disable Plugin** (checkbox)
- **Rotator Service URL** (text field) - contoh: `https://yourdomain.com/redirect`
- **Default Message** (textarea) - pesan default jika tidak ada custom message
- **Button Position** (select: bottom-left, bottom-right, custom)
- **Custom Position** (number fields: bottom pixels, right/left pixels) - muncul jika pilih custom
- **Button Size** (select: small 50px, medium 65px, large 80px)
- **Show on Pages** (checkboxes: homepage, posts, pages, shop, product pages, etc)
- **Hide on Specific Pages** (text field, comma separated page IDs)
- **Enable Animations** (checkbox: bounce + pulse effects)
- **Custom CSS** (textarea untuk override styling)

### 3. Shortcode Support
**[ge_whatsapp_button]** dengan attributes:
- `message` - custom message untuk link tersebut
- `text` - custom button text (untuk inline mode)
- `style` - style button (floating, inline, custom)
- `size` - size override (small, medium, large, custom)
- `url` - override rotator URL (optional)
- `class` - additional CSS classes

**Examples:**
```
[ge_whatsapp_button message="Saya tertarik dengan produk ini"]
[ge_whatsapp_button message="Mau konsultasi gratis" text="Chat Sekarang" style="inline"]
[ge_whatsapp_button message="Tanya stok produk" size="large"]
```

### 4. CSS Styling (dengan prefix ge-)
**Gunakan CSS yang sudah kita buat sebelumnya:**
- Class names dengan prefix `ge-` (ge-whatsapp-float, ge-wa-icon, etc)
- Gradient background WhatsApp green
- Smooth animations (bounce, pulse, hover effects)
- Responsive design
- Dark mode support

### 5. JavaScript Functionality
**Function untuk redirect:**
```javascript
function geRedirectToWhatsApp(customMessage) {
  const rotatorUrl = ge_whatsapp_vars.rotator_url; // dari wp_localize_script
  const defaultMessage = ge_whatsapp_vars.default_message;
  const message = customMessage || defaultMessage;
  const finalUrl = rotatorUrl + '?message=' + encodeURIComponent(message);
  
  // Optional Google Analytics tracking
  if (typeof gtag !== 'undefined') {
    gtag('event', 'whatsapp_click', {
      'event_category': 'engagement',
      'event_label': 'ge_whatsapp_button'
    });
  }
  
  window.open(finalUrl, '_blank');
}
```

### 6. Link Generator Tool (Admin)
**Di settings page, tambahkan section:**
- **"Link Generator"** untuk admin
- Input field untuk custom message
- Generate button yang output full URL
- Copy to clipboard functionality
- Preview section yang show final rotator URL
- Table untuk save multiple generated links

**Output format:**
```
Generated Link: https://yourdomain.com/redirect?message=Saya%20tertarik%20dengan%20produk
Shortcode: [ge_whatsapp_button message="Saya tertarik dengan produk"]
HTML: <a href="https://yourdomain.com/redirect?message=..." class="ge-wa-custom-link">Chat WhatsApp</a>
```

### 7. WooCommerce Integration (Optional)
**Jika WooCommerce active:**
- Option untuk show button di product pages
- Dynamic message dengan product name: "Saya tertarik dengan {product_name}"
- Option untuk show di cart page
- Option untuk show di checkout page

### 8. Widget Support
**WordPress widget:** "GE WhatsApp Button Widget"
- Bisa ditempatkan di sidebar
- Widget options: message, button text, size
- Support untuk multiple widget instances

## File Structure
```
ge-whatsapp-button/
├── ge-whatsapp-button.php (main plugin file)
├── includes/
│   ├── class-ge-whatsapp-button.php (main class)
│   ├── class-admin.php (admin settings)
│   ├── class-frontend.php (frontend display)
│   └── class-widget.php (widget functionality)
├── admin/
│   ├── css/
│   │   └── admin-style.css
│   ├── js/
│   │   └── admin-script.js
│   └── admin-page.php (settings page template)
├── public/
│   ├── css/
│   │   └── public-style.css (dengan prefix ge-)
│   └── js/
│       └── public-script.js
├── languages/
│   └── ge-whatsapp-button.pot
├── readme.txt
└── uninstall.php
```

## Key Features Summary
✅ **Floating button** dengan animasi yang menarik  
✅ **Admin settings** yang user-friendly  
✅ **Shortcode support** untuk placement fleksibel  
✅ **Link generator tool** untuk admin  
✅ **WooCommerce integration** (optional)  
✅ **Widget support** untuk sidebar  
✅ **Custom CSS** field untuk advanced users  
✅ **Translation ready**  

## Technical Requirements
- **No database tables** needed (hanya wp_options untuk settings)
- **Proper WordPress hooks** (init, admin_init, wp_enqueue_scripts)
- **Nonce verification** untuk security
- **Sanitization** untuk semua input
- **Conditional loading** (CSS/JS hanya load kalau button enabled)
- **Clean uninstall** (remove all plugin options)

## Security & Performance
- Input sanitization dengan `sanitize_text_field()`, `esc_url()`, etc
- Nonce verification untuk admin forms
- Capability checks (`manage_options`)
- Minified CSS/JS untuk production
- No external dependencies

Plugin ini **hanya untuk display button** yang redirect ke external Go rotator service. Simple, lightweight, dan focused pada satu tujuan!
