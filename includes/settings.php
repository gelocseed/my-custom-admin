<?php
/**
 * Settings Page Class for My Custom Admin
 * Handles option registration, rendering the settings screen, and sanitization.
 */

// Safety gate: Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class MCL_Custom_Admin_Settings {

    /**
     * Constructor to hook into WordPress lifecycle
     */
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_settings_menu' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_media_uploader' ] );
    }

    /**
     * Enqueue WP Media scripts only on our settings page
     */
    public function enqueue_media_uploader( $hook ) {
        if ( 'settings_page_my-custom-admin' !== $hook ) {
            return;
        }
        wp_enqueue_media();
    }

    /**
     * Add settings page under "Settings" (Настройки)
     */
    public function add_settings_menu() {
        add_options_page(
            __( 'Кастомная админка', 'my-custom-admin' ),
            __( 'Кастомная админка', 'my-custom-admin' ),
            'manage_options',
            'my-custom-admin',
            [ $this, 'render_settings_page' ]
        );
    }

    /**
     * Register settings, sections, and fields
     */
    public function register_settings() {
        register_setting(
            'mcl_custom_admin_options',
            'mcl_custom_admin_settings',
            [
                'sanitize_callback' => [ $this, 'sanitize_settings' ],
                'default'           => [
                    'logo_url'        => '',
                    'theme'           => 'system',
                    'clean_dashboard' => 0,
                ],
            ]
        );

        add_settings_section(
            'mcl_general_section',
            __( 'Визуальные настройки', 'my-custom-admin' ),
            [ $this, 'render_general_section_desc' ],
            'my-custom-admin'
        );

        add_settings_field(
            'logo_url',
            __( 'Собственный логотип', 'my-custom-admin' ),
            [ $this, 'render_logo_field' ],
            'my-custom-admin',
            'mcl_general_section'
        );

        add_settings_field(
            'theme',
            __( 'Тема оформления', 'my-custom-admin' ),
            [ $this, 'render_theme_field' ],
            'my-custom-admin',
            'mcl_general_section'
        );

        add_settings_field(
            'clean_dashboard',
            __( 'Очистка Dashboard', 'my-custom-admin' ),
            [ $this, 'render_clean_dashboard_field' ],
            'my-custom-admin',
            'mcl_general_section'
        );
    }

    /**
     * Sanitization callback for options
     */
    public function sanitize_settings( $input ) {
        $output = [];

        if ( isset( $input['logo_url'] ) ) {
            $output['logo_url'] = esc_url_raw( $input['logo_url'] );
        }

        if ( isset( $input['theme'] ) && in_array( $input['theme'], [ 'light', 'dark', 'system' ], true ) ) {
            $output['theme'] = $input['theme'];
        } else {
            $output['theme'] = 'system';
        }

        $output['clean_dashboard'] = isset( $input['clean_dashboard'] ) ? 1 : 0;

        return $output;
    }

    /**
     * Render settings section description
     */
    public function render_general_section_desc() {
        echo '<p class="mcl-settings-desc">' . esc_html__( 'Настройте внешний вид админ-панели под ваш бренд. Все изменения стилей применятся автоматически.', 'my-custom-admin' ) . '</p>';
    }

    /**
     * Render Custom Logo Field
     */
    public function render_logo_field() {
        $options  = get_option( 'mcl_custom_admin_settings', [] );
        $logo_url = isset( $options['logo_url'] ) ? $options['logo_url'] : '';
        ?>
        <div class="mcl-logo-uploader-wrap">
            <div class="mcl-logo-preview-box" id="mcl-logo-preview-box">
                <?php if ( ! empty( $logo_url ) ) : ?>
                    <img src="<?php echo esc_url( $logo_url ); ?>" alt="Logo Preview" id="mcl-logo-preview-img">
                <?php else : ?>
                    <span class="mcl-no-logo-text"><?php esc_html_e( 'Логотип не выбран', 'my-custom-admin' ); ?></span>
                <?php endif; ?>
            </div>
            <div class="mcl-uploader-controls">
                <input type="text" 
                       name="mcl_custom_admin_settings[logo_url]" 
                       id="mcl-logo-url-input" 
                       value="<?php echo esc_url( $logo_url ); ?>" 
                       class="regular-text mcl-logo-input" 
                       placeholder="https://example.com/logo.png"
                >
                <button type="button" class="button mcl-upload-btn" id="mcl-upload-logo-button">
                    <?php esc_html_e( 'Выбрать логотип', 'my-custom-admin' ); ?>
                </button>
                <button type="button" class="button button-link-delete mcl-remove-btn" id="mcl-remove-logo-button" <?php echo empty( $logo_url ) ? 'style="display:none;"' : ''; ?>>
                    <?php esc_html_e( 'Удалить', 'my-custom-admin' ); ?>
                </button>
            </div>
        </div>
        <?php
    }

    /**
     * Render Theme Field
     */
    public function render_theme_field() {
        $options = get_option( 'mcl_custom_admin_settings', [] );
        $theme   = isset( $options['theme'] ) ? $options['theme'] : 'system';
        ?>
        <div class="mcl-theme-select-wrap">
            <select name="mcl_custom_admin_settings[theme]" id="mcl-theme-select" class="mcl-theme-dropdown">
                <option value="light" <?php selected( $theme, 'light' ); ?>><?php esc_html_e( 'Светлая', 'my-custom-admin' ); ?></option>
                <option value="dark" <?php selected( $theme, 'dark' ); ?>><?php esc_html_e( 'Тёмная', 'my-custom-admin' ); ?></option>
                <option value="system" <?php selected( $theme, 'system' ); ?>><?php esc_html_e( 'Системная', 'my-custom-admin' ); ?></option>
            </select>
            <p class="description"><?php esc_html_e( 'Выберите цветовую схему для интерфейса панели.', 'my-custom-admin' ); ?></p>
        </div>
        <?php
    }

    /**
     * Render Clean Dashboard Field
     */
    public function render_clean_dashboard_field() {
        $options = get_option( 'mcl_custom_admin_settings', [] );
        $clean   = isset( $options['clean_dashboard'] ) ? (bool)$options['clean_dashboard'] : false;
        ?>
        <label class="mcl-checkbox-label">
            <input type="checkbox" 
                   name="mcl_custom_admin_settings[clean_dashboard]" 
                   value="1" 
                   id="mcl-clean-dashboard-checkbox"
                   <?php checked( $clean, true ); ?>
            >
            <span class="mcl-checkbox-text"><?php esc_html_e( 'Отключить стандартные виджеты WordPress на главной странице админки', 'my-custom-admin' ); ?></span>
        </label>
        <?php
    }

    /**
     * Render Settings Page Layout
     */
    public function render_settings_page() {
        // Double check permissions
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        ?>
        <div class="wrap mcl-settings-page-wrap">
            <div class="mcl-settings-header">
                <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
                <p class="mcl-settings-subtitle"><?php esc_html_e( 'Кастомизация панели управления WordPress в стиле UiPress', 'my-custom-admin' ); ?></p>
            </div>
            
            <div class="mcl-settings-card">
                <form action="options.php" method="post" class="mcl-settings-form">
                    <?php
                    settings_fields( 'mcl_custom_admin_options' );
                    do_settings_sections( 'my-custom-admin' );
                    submit_button( __( 'Сохранить настройки', 'my-custom-admin' ), 'primary mcl-submit-btn' );
                    ?>
                </form>
            </div>
        </div>
        <?php
    }
}
