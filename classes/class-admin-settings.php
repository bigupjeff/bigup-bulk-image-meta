<?php
namespace Bigup\Bulk_Image_Meta;

/**
 * Bigup Bulk Image Meta - Admin Settings.
 *
 * Hook into the WP admin area and add menu options and settings pages.
 *
 * @package bigup_bulk_image_meta
 * @author Jefferson Real <me@jeffersonreal.uk>
 * @copyright Copyright (c) 2023, Jefferson Real
 * @license GPL3+
 * @link https://jeffersonreal.uk
 */

// WordPress dependencies.
use function menu_page_url;
use function add_submenu_page;
use function get_option;
use function add_action;
use function settings_fields;
use function do_settings_sections;
use function submit_button;
use function sanitize_text_field;
use function add_settings_section;
use function add_settings_field;
use function register_setting;
use function do_shortcode;

class Admin_Settings {


	/**
	 * Settings page menu title to add with add_submenu_page().
	 */
	public $admin_label = 'Bulk Image Meta';


	/**
	 * Settings page slug to add with add_submenu_page().
	 */
	public $page_slug = 'bigup-web-bulk-image-meta';


	/**
	 * Settings group name called by settings_fields().
	 *
	 * To add multiple sections to the same settings page, all settings
	 * registered for that page MUST BE IN THE SAME 'OPTION GROUP'.
	 */
	public $group_name = 'group_bigup-web-bulk-image-meta_settings';


	/**
	 * base64 uri svg icon used next to page title.
	 */
	public $icon = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMzIiIGhlaWdodD0iMTMyIj48cGF0aCBmaWxsPSJjdXJyZW50Q29sb3IiIGQ9Ik0wIDB2MTMyaDM1LjRWODcuMmMwLTUuNiAwLTExLjYgMS43LTE2LjcuOC0yLjUgNC40LTMuNyA3LjEtMy43aDM0LjVjMy4yIDAgNi45LjEgOC4yIDEuMiAyLjMgMS44IDEuOSA3LjIgMi4xIDEwLjUuNCA0LjkgMSAxNC4yLS41IDE1LjYtMy4zIDMuNC0yLjggNC05LjIgMTAuMS0xLjggMS40LTYtLjktNS4zLTQuNC43LTMuNiAzLjQtOS43IDMuNC0xMS40IDAtMS43LTIgLjgtMi44IDAtLjMtLjQtLjYtLjktLjgtMS42LS43LTIuNCA0LjgtNy43IDQuMi04LjgtLjktMS4zLTQuMyA3LTYuNCA1LS42LS41LTIuMS00LjktMi44LTUtMSAwIDEuOCA0LjguOCA3LjktLjcgMi0zLjIgMi44LTUuMiAzLTIuNi41LTEzLjMtMTAuMS0xNC05LjUtLjguNyAxMC44IDEwLjcgMTIuNCAxNCAxLjMgMi4xIDIuMyA3LjUgMS43IDguMS0uNi43LTEwLjktNC05LjItMS41IDEuOCAyLjYgMTAgMy4yIDEzLjYgMy44IDEuMS4yIDMgLjEgNC42IDIuNS4zLjQtMi42LS40LTUuMy0xLTIuNi0uMy01LjQtMS01LjktLjgtLjcuNSAyIDMuMiAyLjggMy40IDEuMS40IDExLjUtLjUgMTIuMi0uNyAyLjgtMSAzLjktMS42IDQuMy0yIDUuOC02LjcgOS40LTkgOS42LTEyLjEuMi0zLjEtLjQtMTMgMi4zLTE0LjggMi42LTEuOCA1LjMuMSA2LjUgNS44IDEuMiA1LjcgMy40IDUuNiA0LjQgMTAuOCAxIDUuMi0zLjMgMTUuOS01LjYgMjEuOS0yLjIgNi03LjQgNy42LTEwLjYgOS42LTMuMyAyLTYuNyAzLjUtMTAuOCA0LjMtMi45LjYtNy41IDEuMS05LjkgMS4zSDEzMlYwSDY2czcuNC41IDExLjQgMS4zUzg1IDMuNyA4OC4yIDUuN2MzLjIgMiA4LjQgMy42IDEwLjYgOS42IDIuMyA2IDYuNyAxNi42IDUuNiAyMS44LTEgNS4zLTMuMiA1LjEtNC40IDEwLjgtMS4yIDUuNy0zLjkgNy43LTYuNSA1LjktMi43LTEuOS0yLjEtMTEuOC0yLjMtMTQuOC0uMi0zLjEtMy44LTUuNS05LjYtMTIuMS0uNC0uNS0xLjUtMS4xLTQuMy0yLS43LS4yLTExLTEuMi0xMi4yLS44LS44LjItMy41IDMtMi44IDMuNC41LjMgMy4zLS40IDUuOS0uOSAyLjctLjQgNS42LTEuMyA1LjMtLjlDNzIgMjguMSA3MCAyOCA2OSAyOC4yYy0zLjUuNi0xMS44IDEuMi0xMy42IDMuOC0xLjcgMi42IDguNi0yLjIgOS4yLTEuNS42LjctLjQgNi0xLjcgOC4yLTEuNiAzLjMtMTMuMiAxMy4yLTEyLjQgMTMuOS43LjcgMTEuNC0xMCAxNC05LjUgMiAuMyA0LjUgMSA1LjIgMyAxIDMuMS0xLjcgOC0uOCA3LjguNyAwIDIuMi00LjQgMi44LTUgMi0yIDUuNSA2LjQgNi40IDUgLjYtMS00LjktNi4zLTQuMi04LjcuMi0uNy41LTEuMi44LTEuNS44LTEgMi44IDEuNiAyLjggMCAwLTEuOC0yLjctNy44LTMuNC0xMS40LS43LTMuNiAzLjUtNS45IDUuMy00LjUgNi40IDYgNiA2LjggOS4yIDEwLjEgMS40IDEuNSAxIDEwLjcuNSAxNS43LS4yIDMuMi4yIDguNi0yLjEgMTAuNS0yIDEuNS04LjggMS4xLTEyIDEuMUg0NC4yYy0yLjcgMC02LjMtMS4xLTcuMS0zLjctMS43LTUtMS43LTExLTEuNy0xNi43VjBaIi8+PC9zdmc+Cg==';


	/**
	 * Initialise the class by hooking into the admin interface.
	 */
	public function __construct() {
		add_action( 'bigup_settings_dashboard_entry', array( &$this, 'echo_plugin_settings_link' ) );
		new Admin_Settings_Parent();
		add_action( 'admin_menu', array( &$this, 'register_admin_menu' ), 99 );
		add_action( 'admin_init', array( &$this, 'register_settings' ) );
	}


	/**
	 * Add admin menu option to sidebar
	 */
	public function register_admin_menu() {
		add_submenu_page(
			Admin_Settings_Parent::$page_slug,       // parent_slug.
			$this->admin_label . ' Settings',        // page_title.
			$this->admin_label,                      // menu_title.
			'manage_options',                        // capability.
			$this->page_slug,                        // menu_slug.
			array( &$this, 'create_settings_page' ), // function.
			null,                                    // position.
		);
	}


	/**
	 * Echo a link to this plugin's settings page.
	 */
	public function echo_plugin_settings_link() {
		?>

		<a href="/wp-admin/admin.php?page=<?php echo $this->page_slug; ?>">
			Go to <?php echo $this->admin_label; ?> settings
		</a>
		<br>

		<?php
	}


	/**
	 * Create Plugin Settings Page
	 */
	public function create_settings_page() {
		?>

		<div class="wrap">

			<h1>
				<span class="dashicons-bigup-logo" style="font-size: 2em; margin-right: 0.2em;"></span>
				Bigup Web Bulk Image Meta Settings
			</h1>

			<p>
				Update image meta in bulk.
			</p>

			<form method="post" action="options.php">

				<?php
					/* Setup hidden input functionality */
					settings_fields( $this->group_name );

					/* Print the input fields */
					do_settings_sections( $this->page_slug );

					/* Print the submit button */
					submit_button( 'Save' );
				?>

			</form>

		</div>

		<?php
	}


	/**
	 * Output Form Fields - Example Option
	 */
	public function echo_field_example_option() {
		echo '<input type="text" name="example-option" id="example-option" value="' . get_option( 'example-option' ) . '" required>';
	}


	/**
	 * Register all settings fields and call their functions to build the page.
	 *
	 * E.g. add_settings_section( $id, $title, $callback, $page )
	 * E.g. add_settings_field( $id, $title, $callback, $page, $section, $args )
	 * E.g. register_setting( $option_group, $option_name, $sanitize_callback )
	 */
	public function register_settings() {

		$group = $this->group_name;
		$page  = $this->page_slug;

		/**
		 * Register section and fields - Example Setting
		 */
		$section = 'section_example';
		add_settings_section( $section, 'Example Options Section', null, $page );

			add_settings_field( 'example-option', 'Example Option', array( &$this, 'echo_field_example_option' ), $page, $section );
			register_setting( $group, 'example-option', array( &$this, 'validate_text' ) );
	}


	// Below are example vaidation functions. This could be outsourced to a library or another class.


	/**
	 * Validate a text field.
	 *
	 * @param string $text Value from a text input field.
	 */
	public function validate_text( $text ) {
		$clean_text = sanitize_text_field( $text );
		return $clean_text;
	}


	/**
	 * Validate a checkbox.
	 *
	 * @param bool $checkbox Value from a checkbox input field.
	 */
	public function sanitise_checkbox( $checkbox ) {
		$bool_checkbox = (bool) $checkbox;
		return $bool_checkbox;
	}

}
