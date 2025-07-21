/**
 * Admin JavaScript for GE WhatsApp Button
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Show/hide custom position fields
        function toggleCustomPosition() {
            const position = $('#position').val();
            const customFields = $('#custom_bottom, #custom_right').closest('tr');
            
            if (position === 'custom') {
                customFields.show();
            } else {
                customFields.hide();
            }
        }
        
        // Initialize custom position toggle
        toggleCustomPosition();
        $('#position').on('change', toggleCustomPosition);
        
        // Preview button functionality
        function updatePreview() {
            const size = $('#button_size').val();
            const animations = $('#enable_animations').is(':checked');
            const position = $('#position').val();
            
            let previewClass = 'ge-preview-button ge-wa-' + size;
            if (animations) {
                previewClass += ' ge-wa-animated';
            }
            
            $('.ge-preview-button').attr('class', previewClass);
            
            // Update position info
            let positionText = 'Position: ';
            switch (position) {
                case 'bottom-left':
                    positionText += 'Bottom Left';
                    break;
                case 'bottom-right':
                    positionText += 'Bottom Right';
                    break;
                case 'custom':
                    const bottom = $('#custom_bottom').val() || '20';
                    const right = $('#custom_right').val() || '20';
                    positionText += `Custom (${bottom}px from bottom, ${right}px from right)`;
                    break;
                default:
                    positionText += 'Bottom Right';
            }
            
            $('#ge-position-info').text(positionText);
        }
        
        // Add preview section if it doesn't exist
        if ($('.ge-preview-section').length === 0) {
            $('form').after(`
                <div class="ge-preview-section">
                    <h3>Preview</h3>
                    <div class="ge-preview-button ge-wa-icon">
                    </div>
                    <p id="ge-position-info"></p>
                </div>
            `);
        }
        
        // Update preview on changes
        $('#button_size, #enable_animations, #position, #custom_bottom, #custom_right').on('change input', updatePreview);
        updatePreview();
        
        
        // Test rotator URL functionality
        $('#test-rotator-url').on('click', function() {
            const rotatorUrl = $('#rotator_url').val().trim();
            
            if (!rotatorUrl) {
                alert('Please enter a Rotator Service URL first');
                return;
            }
            
            const testMessage = 'Test message from GE WhatsApp Button plugin';
            let testUrl = rotatorUrl;
            if (testMessage && testMessage.trim() !== '') {
                testUrl += '?message=' + encodeURIComponent(testMessage);
            }
            
            window.open(testUrl, '_blank');
        });
        
        // Add test button if it doesn't exist
        if ($('#test-rotator-url').length === 0) {
            $('#rotator_url').after(`
                <button type="button" id="test-rotator-url" class="button button-secondary" style="margin-left: 10px;">
                    Test URL
                </button>
                <p class="ge-help-text">Test your rotator URL to make sure it's working correctly</p>
            `);
        }
        
        // Real-time validation
        $('#rotator_url').on('input', function() {
            const url = $(this).val().trim();
            const isValid = url === '' || /^https?:\/\/.+/.test(url);
            
            $(this).toggleClass('invalid', !isValid);
            
            if (!isValid && url !== '') {
                $(this).after('<span class="ge-validation-error">Please enter a valid URL starting with http:// or https://</span>');
            } else {
                $(this).siblings('.ge-validation-error').remove();
            }
        });
        
        // Form validation before submit
        $('form').on('submit', function(e) {
            const rotatorUrl = $('#rotator_url').val().trim();
            
            if (rotatorUrl && !/^https?:\/\/.+/.test(rotatorUrl)) {
                e.preventDefault();
                alert('Please enter a valid Rotator Service URL');
                $('#rotator_url').focus();
                return false;
            }
        });
        
        // Initialize tooltips if available
        if (typeof wp !== 'undefined' && wp.a11y) {
            $('.ge-help-text').each(function() {
                wp.a11y.speak($(this).text(), 'polite');
            });
        }
        
    });
    
})(jQuery);