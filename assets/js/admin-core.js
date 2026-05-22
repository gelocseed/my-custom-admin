/**
 * My Custom Admin - Core JS Engine
 * Handles Media Uploads, real-time theme toggling, and dashboard micro-interactions.
 */
jQuery(document).ready(function($) {
    'use strict';

    /* ==========================================================================
       1. Theme Real-time Switcher
       ========================================================================== */
    var $themeSelect = $('#mcl-theme-select');
    if ($themeSelect.length) {
        $themeSelect.on('change', function() {
            var selectedTheme = $(this).val();
            
            // Remove previous theme classes and apply the new one
            $('body')
                .removeClass('mcl-theme-light mcl-theme-dark mcl-theme-system')
                .addClass('mcl-theme-' + selectedTheme);
        });
    }

    /* ==========================================================================
       2. WordPress Media Uploader for Custom Logo
       ========================================================================== */
    var fileFrame;
    var $uploadBtn = $('#mcl-upload-logo-button');
    var $removeBtn = $('#mcl-remove-logo-button');
    var $logoInput = $('#mcl-logo-url-input');
    var $previewBox = $('#mcl-logo-preview-box');

    if ($uploadBtn.length) {
        $uploadBtn.on('click', function(e) {
            e.preventDefault();

            // If the media frame already exists, reopen it.
            if (fileFrame) {
                fileFrame.open();
                return;
            }

            // Create the media frame.
            fileFrame = wp.media.frames.file_frame = wp.media({
                title: 'Выберите или загрузите логотип',
                button: {
                    text: 'Использовать этот логотип'
                },
                multiple: false // Only allow single image selection
            });

            // When an image is selected, run a callback.
            fileFrame.on('select', function() {
                // Get the attachment details
                var attachment = fileFrame.state().get('selection').first().toJSON();

                // Get the image URL (prefer original/full or fallback)
                var imageUrl = attachment.url;
                if (attachment.sizes && attachment.sizes.thumbnail) {
                    // We store full URL but can use smaller preview
                }

                // Fill the text input
                $logoInput.val(imageUrl);

                // Update the preview box
                $previewBox.html('<img src="' + imageUrl + '" id="mcl-logo-preview-img" alt="Logo Preview">');

                // Show the remove button
                $removeBtn.show();
            });

            // Open the modal
            fileFrame.open();
        });
    }

    // Handle logo removal
    if ($removeBtn.length) {
        $removeBtn.on('click', function(e) {
            e.preventDefault();

            // Clear input value
            $logoInput.val('');

            // Reset preview box HTML
            $previewBox.html('<span class="mcl-no-logo-text">Логотип не выбран</span>');

            // Hide remove button itself
            $(this).hide();
        });
    }
});
