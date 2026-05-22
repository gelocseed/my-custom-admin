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

    /* ==========================================================================
       3. Lucide Icons Mapper for Admin Menu (shadcn/ui style)
       ========================================================================== */
    var dashiconMap = {
        'dashicons-dashboard': 'layout-dashboard',
        'dashicons-admin-post': 'file-text',
        'dashicons-admin-media': 'image',
        'dashicons-admin-links': 'link',
        'dashicons-admin-page': 'files',
        'dashicons-admin-comments': 'message-square',
        'dashicons-admin-appearance': 'palette',
        'dashicons-admin-plugins': 'plug',
        'dashicons-admin-users': 'users',
        'dashicons-admin-tools': 'wrench',
        'dashicons-admin-settings': 'settings',
        'dashicons-admin-generic': 'box',
        'dashicons-admin-home': 'home',
        'dashicons-admin-network': 'globe',
        'dashicons-admin-site': 'globe',
        'dashicons-cart': 'shopping-cart',
        'dashicons-feedback': 'message-circle',
        'dashicons-portfolio': 'briefcase',
        'dashicons-products': 'shopping-bag',
        'dashicons-chart-area': 'trending-up',
        'dashicons-chart-bar': 'bar-chart-2',
        'dashicons-chart-line': 'line-chart',
        'dashicons-chart-pie': 'pie-chart',
        'dashicons-welcome-widgets-menus': 'menu',
        'dashicons-welcome-learn-more': 'book-open',
        'dashicons-email': 'mail',
        'dashicons-email-alt': 'mail',
        'dashicons-shield': 'shield',
        'dashicons-translation': 'languages',
        'dashicons-editor-customchar': 'type',
        'dashicons-buddypress-activity': 'activity',
        'dashicons-store': 'store',
        'dashicons-vault': 'lock',
        'dashicons-awards': 'award',
        'dashicons-groups': 'users',
        'dashicons-format-image': 'image',
        'dashicons-format-gallery': 'images',
        'dashicons-format-audio': 'music',
        'dashicons-format-video': 'video',
        'dashicons-format-links': 'link-2',
        'dashicons-format-quote': 'quote',
        'dashicons-format-status': 'info',
        'dashicons-format-aside': 'clipboard',
        'dashicons-format-chat': 'message-square'
    };

    $('#adminmenu li.menu-top').each(function() {
        var $li = $(this);
        var $imgDiv = $li.find('.wp-menu-image');
        if (!$imgDiv.length) return;

        // Skip items that have custom icons from plugins (e.g. Bricks Builder)
        // 1. If it has a Bricks Builder ID or class
        var idAttr = $li.attr('id');
        if (idAttr && idAttr.toLowerCase().indexOf('bricks') !== -1) {
            return;
        }

        // 2. If it contains an <img> tag (common for custom images/SVG icons)
        if ($imgDiv.find('img').length > 0) {
            return;
        }
        // 3. If it contains custom SVG elements (unless they are our Lucide placeholders)
        if ($imgDiv.find('svg').length > 0 && !$imgDiv.find('.mcl-lucide-icon').length) {
            return;
        }
        // 4. If it has a custom background image (inline style or class 'svg')
        if ($imgDiv.hasClass('svg') || ($imgDiv.css('background-image') && $imgDiv.css('background-image') !== 'none')) {
            return;
        }

        // 5. Inspect computed style of ::before pseudo-element and the element itself to detect custom icons/fonts
        if (window.getComputedStyle) {
            var compStyle = window.getComputedStyle($imgDiv[0]);
            var bgImg = compStyle.getPropertyValue('background-image');
            if (bgImg && bgImg !== 'none') {
                return;
            }

            var beforeStyle = window.getComputedStyle($imgDiv[0], '::before');
            if (beforeStyle) {
                var beforeBg = beforeStyle.getPropertyValue('background-image');
                if (beforeBg && beforeBg !== 'none') {
                    return;
                }
                var beforeFont = beforeStyle.getPropertyValue('font-family');
                if (beforeFont) {
                    var cleanFont = beforeFont.replace(/['"]/g, '').trim().toLowerCase();
                    // If there's a custom font family (other than dashicons, lucide, inherit, initial, unset)
                    if (cleanFont && cleanFont !== 'dashicons' && cleanFont !== 'lucide' && cleanFont !== 'inherit' && cleanFont !== 'initial' && cleanFont !== 'unset') {
                        return;
                    }
                }
                var beforeContent = beforeStyle.getPropertyValue('content');
                if (beforeContent && beforeContent !== 'none' && beforeContent !== 'normal' && beforeContent !== '""' && beforeContent !== "''") {
                    if (beforeContent.indexOf('url(') !== -1) {
                        return;
                    }
                }
            }
        }

        // 6. If it has custom non-dashicons icon font classes (must have a class starting with 'dashicons-')
        var hasDashiconClass = false;
        var classAttr = $imgDiv.attr('class');
        if (classAttr) {
            var classes = classAttr.split(/\s+/);
            $.each(classes, function(index, cls) {
                if (cls.indexOf('dashicons-') === 0) {
                    hasDashiconClass = true;
                    return false; // Break $.each
                }
            });
        }
        if (!hasDashiconClass) {
            return; // It's a custom icon font or custom styled item, skip it
        }

        var iconName = null;

        // Try mapping from the dashicon class on .wp-menu-image
        var classAttr = $imgDiv.attr('class');
        if (classAttr) {
            var classes = classAttr.split(/\s+/);
            $.each(classes, function(index, cls) {
                if (dashiconMap[cls]) {
                    iconName = dashiconMap[cls];
                    return false; // Break $.each
                }
            });
        }

        // If no class matched, fall back to matching by element ID
        if (!iconName) {
            var id = $li.attr('id');
            if (id) {
                // e.g. menu-plugins -> dashicons-admin-plugins
                var cleanId = id.replace('menu-', 'dashicons-admin-');
                if (dashiconMap[cleanId]) {
                    iconName = dashiconMap[cleanId];
                } else {
                    // Try layout/dashboard mapping
                    var shortId = id.replace('menu-', '');
                    if (dashiconMap['dashicons-' + shortId]) {
                        iconName = dashiconMap['dashicons-' + shortId];
                    }
                }
            }
        }

        // Default fallback if no match found
        if (!iconName) {
            iconName = 'box';
        }

        // Empty the default Dashicon markup and replace it with a Lucide placeholder icon and add helper class
        $imgDiv.empty().html('<i data-lucide="' + iconName + '" class="mcl-lucide-icon"></i>').addClass('mcl-lucide-replaced');
    });

    // Initialize Lucide icons if the library is loaded
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    /* ==========================================================================
       4. Theme Toggle Button in Admin Bar
       ========================================================================== */
    var $toggleNode = $('#wp-admin-bar-mcl-theme-toggle');
    if ($toggleNode.length) {
        $toggleNode.on('click', function(e) {
            e.preventDefault();
            
            var $body = $('body');
            var currentTheme = 'system';
            
            if ($body.hasClass('mcl-theme-light')) {
                currentTheme = 'light';
            } else if ($body.hasClass('mcl-theme-dark')) {
                currentTheme = 'dark';
            } else if ($body.hasClass('mcl-theme-system')) {
                currentTheme = 'system';
            }
            
            var nextTheme = 'light';
            if (currentTheme === 'light') {
                nextTheme = 'dark';
            } else if (currentTheme === 'dark') {
                nextTheme = 'light';
            } else {
                // If system, toggle based on prefers-color-scheme
                var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                nextTheme = prefersDark ? 'light' : 'dark';
            }
            
            // Apply theme class to body
            $body.removeClass('mcl-theme-light mcl-theme-dark mcl-theme-system')
                 .addClass('mcl-theme-' + nextTheme);
                 
            // Update settings dropdown if on the settings page
            var $settingsSelect = $('#mcl-theme-select');
            if ($settingsSelect.length) {
                $settingsSelect.val(nextTheme);
            }
            
            // Save selection to WordPress options via AJAX
            $.post(ajaxurl, {
                action: 'mcl_save_theme',
                theme: nextTheme
            });
        });
    }
});
