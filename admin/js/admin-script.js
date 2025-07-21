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
                    <div class="ge-preview-button">
                        <img src="data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23fff' d='M3.516 3.516c4.686-4.686 12.284-4.686 16.97 0s4.686 12.283 0 16.97a12 12 0 0 1-13.754 2.299l-5.814.735a.392.392 0 0 1-.438-.44l.748-5.788A12 12 0 0 1 3.517 3.517zm3.61 17.043.3.158a9.85 9.85 0 0 0 11.534-1.758c3.843-3.843 3.843-10.074 0-13.918s-10.075-3.843-13.918 0a9.85 9.85 0 0 0-1.747 11.554l.16.303-.51 3.942a.196.196 0 0 0 .219.22zm6.534-7.003-.933 1.164a9.84 9.84 0 0 1-3.497-3.495l1.166-.933a.79.79 0 0 0 .23-.94L9.561 6.96a.79.79 0 0 0-.924-.445l-2.023.524a.797.797 0 0 0-.588.88 11.754 11.754 0 0 0 10.005 10.005.797.797 0 0 0 .88-.587l.525-2.023a.79.79 0 0 0-.445-.923L14.6 13.327a.79.79 0 0 0-.94.23z'%3E%3C/svg%3E" alt="WhatsApp" />
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