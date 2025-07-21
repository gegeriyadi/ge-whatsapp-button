/**
 * GE WhatsApp Button JavaScript
 */

(function($) {
    'use strict';
    
    /**
     * Main function to redirect to WhatsApp
     */
    window.geRedirectToWhatsApp = function(customMessage) {
        // Get values from localized script
        const rotatorUrl = ge_whatsapp_vars.rotator_url;
        const defaultMessage = ge_whatsapp_vars.default_message;
        
        // Use custom message or fall back to default
        const message = customMessage || defaultMessage;
        
        // Validate rotator URL
        if (!rotatorUrl) {
            console.error('GE WhatsApp Button: Rotator URL not configured');
            return false;
        }
        
        // Build final URL
        const finalUrl = rotatorUrl + '?message=' + encodeURIComponent(message);
        
        // Optional Google Analytics tracking
        if (typeof gtag !== 'undefined') {
            gtag('event', 'whatsapp_click', {
                'event_category': 'engagement',
                'event_label': 'ge_whatsapp_button',
                'value': 1
            });
        }
        
        // Optional Google Analytics Universal Analytics tracking
        if (typeof ga !== 'undefined') {
            ga('send', 'event', 'engagement', 'whatsapp_click', 'ge_whatsapp_button', 1);
        }
        
        // Optional Facebook Pixel tracking
        if (typeof fbq !== 'undefined') {
            fbq('track', 'Contact', {
                source: 'ge_whatsapp_button'
            });
        }
        
        // Open WhatsApp link
        window.open(finalUrl, '_blank');
        
        return false;
    };
    
    /**
     * Initialize when document is ready
     */
    $(document).ready(function() {
        
        // Add hover effects for better UX
        $('.ge-wa-icon, .ge-wa-button').on('mouseenter', function() {
            $(this).addClass('ge-wa-hover');
        }).on('mouseleave', function() {
            $(this).removeClass('ge-wa-hover');
        });
        
        // Handle click events for custom links
        $(document).on('click', '.ge-wa-custom-link', function(e) {
            e.preventDefault();
            const href = $(this).attr('href');
            if (href && href !== '#') {
                window.open(href, '_blank');
            }
        });
        
        // Add keyboard support for accessibility
        $('.ge-wa-icon, .ge-wa-button').on('keydown', function(e) {
            if (e.keyCode === 13 || e.keyCode === 32) { // Enter or Space
                e.preventDefault();
                $(this).click();
            }
        });
        
        // Add touch support for mobile devices
        if ('ontouchstart' in window) {
            $('.ge-wa-icon, .ge-wa-button').on('touchstart', function() {
                $(this).addClass('ge-wa-touch');
            }).on('touchend', function() {
                const self = this;
                setTimeout(function() {
                    $(self).removeClass('ge-wa-touch');
                }, 150);
            });
        }
        
        // Performance optimization: Preload WhatsApp URL on hover
        let preloadTimeout;
        $('.ge-wa-icon, .ge-wa-button').on('mouseenter', function() {
            clearTimeout(preloadTimeout);
            preloadTimeout = setTimeout(function() {
                // Preload the rotator URL to improve click response time
                if (ge_whatsapp_vars.rotator_url) {
                    const link = document.createElement('link');
                    link.rel = 'prefetch';
                    link.href = ge_whatsapp_vars.rotator_url;
                    document.head.appendChild(link);
                }
            }, 500);
        }).on('mouseleave', function() {
            clearTimeout(preloadTimeout);
        });
        
        // Add viewport-based animations
        if (window.IntersectionObserver) {
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('ge-wa-visible');
                    }
                });
            }, {
                threshold: 0.1
            });
            
            document.querySelectorAll('.ge-whatsapp-float').forEach(function(el) {
                observer.observe(el);
            });
        }
        
        // Handle window resize for responsive positioning
        let resizeTimeout;
        $(window).on('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function() {
                // Ensure button stays visible on resize
                $('.ge-whatsapp-float').each(function() {
                    const $button = $(this);
                    const buttonRect = this.getBoundingClientRect();
                    const viewportWidth = $(window).width();
                    const viewportHeight = $(window).height();
                    
                    // Adjust position if button is outside viewport
                    if (buttonRect.right > viewportWidth) {
                        $button.css('right', '10px').css('left', 'auto');
                    }
                    if (buttonRect.bottom > viewportHeight) {
                        $button.css('bottom', '10px').css('top', 'auto');
                    }
                });
            }, 250);
        });
        
        // Add smooth scroll behavior when button is clicked
        $('.ge-wa-icon, .ge-wa-button').on('click', function(e) {
            // Add a subtle animation feedback
            const $this = $(this);
            $this.addClass('ge-wa-clicked');
            setTimeout(function() {
                $this.removeClass('ge-wa-clicked');
            }, 200);
        });
        
        // Debug mode (only if WordPress debug is enabled)
        if (typeof ge_whatsapp_debug !== 'undefined' && ge_whatsapp_debug) {
            console.log('GE WhatsApp Button Debug Info:', {
                rotator_url: ge_whatsapp_vars.rotator_url,
                default_message: ge_whatsapp_vars.default_message,
                buttons_found: $('.ge-wa-icon, .ge-wa-button').length
            });
        }
        
    });
    
    /**
     * Handle window load for final optimizations
     */
    $(window).on('load', function() {
        // Add loaded class for CSS transitions
        $('.ge-whatsapp-float').addClass('ge-wa-loaded');
        
        // Remove any loading states
        $('.ge-wa-loading').removeClass('ge-wa-loading');
    });
    
})(jQuery);