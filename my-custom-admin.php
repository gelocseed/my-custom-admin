<?php
/**
 * Plugin Name: My Custom Admin
 * Description: Modern custom WordPress Dashboard
 * Version: 1.0.3
 * Author: Oleg Desco
 * Requires at least: 6.0
 * Requires PHP: 8.2
 * Text Domain: my-custom-admin
 * License: GPL v2 or later
 * GitHub Plugin URI: gelocseed/my-custom-admin
 * Primary Branch: main
 */

// Safety gate: Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// PHP 8.2 Compatibility Check
if ( version_compare( PHP_VERSION, '8.2', '<' ) ) {
    add_action( 'admin_notices', function() {
        ?>
        <div class="error notice">
            <p><?php echo esc_html__( 'My Custom Admin requires PHP 8.2 or higher. Your current PHP version is ' . PHP_VERSION . '. Please upgrade PHP to activate the plugin features.', 'my-custom-admin' ); ?></p>
        </div>
        <?php
    } );
    return;
}

// Define Constants
define( 'MCL_VERSION', '1.0.3' );
define( 'MCL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MCL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include Modular Files
require_once MCL_PLUGIN_DIR . 'includes/settings.php';

// Initialize Settings
if ( class_exists( 'MCL_Custom_Admin_Settings' ) ) {
    new MCL_Custom_Admin_Settings();
}

/**
 * Enqueue styles and scripts for WordPress Dashboard
 */
add_action( 'admin_enqueue_scripts', function( $hook ) {
    // 1. Theme variables (CSS variables)
    wp_enqueue_style( 'mcl-themes', MCL_PLUGIN_URL . 'assets/css/themes.css', [], MCL_VERSION );

    // 2. Base structural CSS
    wp_enqueue_style( 'mcl-admin-base', MCL_PLUGIN_URL . 'assets/css/admin-base.css', [ 'mcl-themes' ], MCL_VERSION );

    // Lucide Icons CDN script
    wp_enqueue_script( 'lucide-icons', 'https://unpkg.com/lucide@0.473.0/dist/umd/lucide.min.js', [], '0.473.0', true );

    // 3. Core JavaScript (theme switching, media uploader, animations)
    wp_enqueue_script( 'mcl-admin-core', MCL_PLUGIN_URL . 'assets/js/admin-core.js', [ 'jquery', 'lucide-icons' ], MCL_VERSION, true );

    // Localize theme option for live JS toggling if needed
    $options = get_option( 'mcl_custom_admin_settings', [] );
    $theme   = isset( $options['theme'] ) ? $options['theme'] : 'system';
    wp_localize_script( 'mcl-admin-core', 'mclAdminParams', [
        'theme' => $theme,
    ] );
} );

/**
 * Enqueue styles and scripts for Login Page (wp-login.php)
 */
add_action( 'login_enqueue_scripts', function() {
    // Load theme variables
    wp_enqueue_style( 'mcl-themes', MCL_PLUGIN_URL . 'assets/css/themes.css', [], MCL_VERSION );
    // Load layout styles (which will handle login screen as well)
    wp_enqueue_style( 'mcl-login-base', MCL_PLUGIN_URL . 'assets/css/admin-base.css', [ 'mcl-themes' ], MCL_VERSION );
} );

/**
 * Add custom classes to admin body based on settings
 */
add_filter( 'admin_body_class', 'mcl_append_admin_body_classes' );
add_filter( 'login_body_class', 'mcl_append_login_body_classes' );

function mcl_append_admin_body_classes( $classes ) {
    $options = get_option( 'mcl_custom_admin_settings', [] );
    $theme   = isset( $options['theme'] ) ? $options['theme'] : 'system';
    
    // Add custom base class and active theme class
    $classes .= ' mcl-custom-admin';
    $classes .= ' mcl-theme-' . esc_attr( $theme );
    
    return $classes;
}

function mcl_append_login_body_classes( $classes ) {
    $options = get_option( 'mcl_custom_admin_settings', [] );
    $theme   = isset( $options['theme'] ) ? $options['theme'] : 'system';
    
    // Add custom base class and active theme class for login page
    $classes[] = 'mcl-custom-admin';
    $classes[] = 'mcl-theme-' . esc_attr( $theme );
    
    return $classes;
}

/**
 * Customize the wp-login.php logo with the custom uploaded logo
 */
add_action( 'login_head', function() {
    $options = get_option( 'mcl_custom_admin_settings', [] );
    $logo    = isset( $options['logo_url'] ) ? $options['logo_url'] : '';
    
    if ( ! empty( $logo ) ) {
        ?>
        <style type="text/css">
            #login h1 a {
                background-image: url(<?php echo esc_url( $logo ); ?>) !important;
                background-size: contain !important;
                background-position: center !important;
                width: 100% !important;
                height: 80px !important;
                margin-bottom: 20px !important;
            }
        </style>
        <?php
    }
} );

/**
 * Customize login logo links
 */
add_filter( 'login_headerurl', function() {
    return home_url();
} );

add_filter( 'login_headertext', function() {
    return get_bloginfo( 'name' );
} );

/**
 * Handle custom admin logo and remove WordPress branding in Admin Bar
 */
add_action( 'wp_before_admin_bar_render', function() {
    global $wp_admin_bar;
    
    // Remove standard WordPress logo node
    $wp_admin_bar->remove_menu( 'wp-logo' );
    
    // Get custom logo
    $options = get_option( 'mcl_custom_admin_settings', [] );
    $logo    = isset( $options['logo_url'] ) ? $options['logo_url'] : '';
    
    if ( ! empty( $logo ) ) {
        $wp_admin_bar->add_menu( [
            'id'    => 'mcl-admin-logo',
            'title' => '<img src="' . esc_url( $logo ) . '" class="mcl-admin-bar-logo" alt="Logo">',
            'href'  => admin_url(),
            'meta'  => [
                'title' => get_bloginfo( 'name' ),
            ],
        ] );
    }

    // Add theme toggle node to the top bar (placed on the right side next to the user profile menu)
    $wp_admin_bar->add_menu( [
        'id'     => 'mcl-theme-toggle',
        'parent' => 'top-secondary',
        'title'  => '<span class="ab-icon mcl-theme-toggle-icon"><i data-lucide="moon" class="mcl-theme-icon-dark"></i><i data-lucide="sun" class="mcl-theme-icon-light"></i></span>',
        'href'   => '#',
        'meta'   => [
            'title' => __( 'Переключить тему', 'my-custom-admin' ),
            'class' => 'mcl-theme-toggle-node',
        ],
    ] );
} );

/**
 * Remove visual clutter - default WordPress Dashboard widgets if enabled
 */
add_action( 'wp_dashboard_setup', function() {
    $options = get_option( 'mcl_custom_admin_settings', [] );
    $clean   = isset( $options['clean_dashboard'] ) ? (bool)$options['clean_dashboard'] : false;
    
    if ( $clean ) {
        // Remove standard dashboard widgets
        remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );       // WordPress Events and News
        remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );   // Quick Draft
        remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );    // At a Glance
        remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );     // Activity
        remove_action( 'welcome_panel', 'wp_welcome_panel' );              // Welcome Panel
    }
}, 999 );

/**
 * AJAX endpoint to save theme changes dynamically from the top bar switcher
 */
add_action( 'wp_ajax_mcl_save_theme', function() {
    // Check permission
    if ( ! current_user_can( 'read' ) ) {
        wp_send_json_error( 'Forbidden', 403 );
    }
    
    $theme = isset( $_POST['theme'] ) ? sanitize_text_field( $_POST['theme'] ) : 'system';
    if ( in_array( $theme, [ 'light', 'dark', 'system' ], true ) ) {
        $options = get_option( 'mcl_custom_admin_settings', [] );
        $options['theme'] = $theme;
        update_option( 'mcl_custom_admin_settings', $options );
        wp_send_json_success();
    } else {
        wp_send_json_error( 'Invalid theme' );
    }
} );
