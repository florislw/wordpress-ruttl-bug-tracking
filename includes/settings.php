<?php
/**
 * Adds a settings page for the plugin in the WordPress admin.
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class Flvl_Ruttl_Bug_Tracking_Settings
 *
 * Handles the settings page for the Ruttl Bug Tracking plugin.
 */
class Flvl_Ruttl_Bug_Tracking_Settings {
	public const OPTION_NAME = 'flvl_ruttl_bug_tracking_settings';
	public const PAGE_SLUG = 'flvl-ruttl-bug-tracking';

	/**
	 * Default values used in registering and retrieving settings.
	 */
	public const DEFAULTS = [
		'project_id'             => '',
		'include_only_logged_in' => true,
	];

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_settings_page' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );

		register_deactivation_hook( FLVL_RUTTL_BUGTRACKING_PLUGIN_FILE, [ self::class, 'delete_settings' ] );
	}

	/**
	 * Adds a settings page as a submenu item under the Settings menu.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function add_settings_page(): void {
		add_options_page(
			__( 'Ruttl Bug Tracking', self::PAGE_SLUG ),
			__( 'Ruttl Bug Tracking', self::PAGE_SLUG ),
			'manage_options',
			self::PAGE_SLUG,
			[ $this, 'render_settings_page' ]
		);
	}

	/**
	 * Renders the settings page.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function render_settings_page(): void {
		?>
        <div class="wrap">
            <h1><?= esc_html__( 'Ruttl Bug Tracking', self::PAGE_SLUG ); ?></h1>
            <form action="options.php" method="post">
				<?php
				settings_fields( self::PAGE_SLUG );
				do_settings_sections( self::PAGE_SLUG );
				submit_button();
				?>
            </form>
        </div>
		<?php
	}

	/**
	 * Registers the settings.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function register_settings(): void {
		register_setting(
			self::PAGE_SLUG,
			self::OPTION_NAME,
			[
				'type'              => 'string',
				'description'       => 'Settings for the Ruttl Bug Tracking plugin.',
				'sanitize_callback' => [ $this, 'sanitize_settings' ],
				'show_in_rest'      => false,
				'default'           => self::DEFAULTS,
			]
		);

		add_settings_section(
			self::PAGE_SLUG,
			__( 'Settings', self::PAGE_SLUG ),
			'',
			self::PAGE_SLUG
		);

		add_settings_field(
			'project_id',
			__( 'Project ID', self::PAGE_SLUG ),
			[ $this, 'render_project_id_field' ],
			self::PAGE_SLUG,
			self::PAGE_SLUG
		);

		add_settings_field(
			'include_only_logged_in',
			__( 'Exclude guests', self::PAGE_SLUG ),
			[ $this, 'render_include_only_logged_in_field' ],
			self::PAGE_SLUG,
			self::PAGE_SLUG
		);
	}

	/**
	 * Renders the Project ID field.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function render_project_id_field(): void {
		$value = self::get_setting( 'project_id' );
		?>
        <input type="text" name="<?= self::OPTION_NAME; ?>[project_id]"
               value="<?= esc_attr( $value ) ?>">
        <p class="description">
			<?= esc_html__( 'The project ID can be found in the Ruttl dashboard URL.', self::PAGE_SLUG ); ?>
        </p>
		<?php
	}

	/**
	 * Renders the Include only logged-in users field.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function render_include_only_logged_in_field(): void {
		$value = self::get_setting( 'include_only_logged_in' );

		?>
        <input type="checkbox" name="<?= self::OPTION_NAME; ?>[include_only_logged_in]"
               value="1" <?= checked( $value ?? '', '1' ); ?>>
        <p class="description">
			<?= esc_html__( 'If checked, the tool is only displayed for logged in users.', self::PAGE_SLUG ); ?>
        </p>
		<?php
	}

	/**
	 * Sanitizes the settings.
	 *
	 * @param array $settings The settings to sanitize.
	 *
	 * @return array The sanitized settings.
	 *
	 * @since 1.0.0
	 */
	public function sanitize_settings( array $settings ): array {
		return [
			'project_id'             => sanitize_text_field( $settings['project_id'] ?? '' ),
			'include_only_logged_in' => sanitize_text_field( $settings['include_only_logged_in'] ?? '' ),
		];
	}

	/**
	 * Delete the settings when the plugin is deleted.
	 */
	public static function delete_settings(): void {
		delete_option( self::OPTION_NAME );
	}

	/**
	 * Get a specific setting by key
	 *
	 * @param string $key The key of the setting to get.
	 *
	 * @return mixed The setting value.
	 *
	 * @since 1.0.0
	 */
	public static function get_setting( string $key ): mixed {
		$settings = get_option( self::OPTION_NAME ) ?: [];

		return $settings[ $key ] ?? ( self::DEFAULTS[ $key ] ?? '' );
	}

	/**
	 * Returns the URL of the settings page.
	 *
	 * @return string The URL of the settings page.
	 *
	 * @since 1.0.0
	 */
	public static function get_settings_url(): string {
		return admin_url( 'options-general.php?page=flvl-ruttl-bug-tracking' );
	}
}

// Initialize the settings page.
new Flvl_Ruttl_Bug_Tracking_Settings();
