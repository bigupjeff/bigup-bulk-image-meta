<?php
namespace Bigup\Plugin_Bootstrap;

/**
 * Plugin Name: Bigup Web: Plugin Bootstrap
 * Plugin URI: https://jeffersonreal.uk
 * Description: A bootstrap project for any WordPress plugin.
 * Version: 0.1
 * Author: Jefferson Real
 * Author URI: https://jeffersonreal.uk
 * License: GPL2
 *
 * @package bigup_plugin_bootstrap
 * @version 0.1
 * @author Jefferson Real <me@jeffersonreal.uk>
 * @copyright Copyright (c) 2023, Jefferson Real
 * @license GPL3+
 * @link https://jeffersonreal.uk
 */

/**
 * Set this plugin's base URL constant for use throughout the plugin.
 *
 * There is no in-built WP function to get the base URL for a plugin, so this constant allows us to
 * write relative file references, making code portable.
 */
$plugin_url = plugin_dir_url( __FILE__ );
define( 'BIGUP_WORDPRES_PLUGIN_BOOTSTRAP_URL', $plugin_url );
$plugin_path = plugin_dir_path( __FILE__ );
define( 'BIGUP_WORDPRES_PLUGIN_BOOTSTRAP_PATH', $plugin_path );

/**
 * Load PHP autoloader to ready the classes.
 */
require_once plugin_dir_path( __FILE__ ) . 'classes/autoload.php';

/**
 * Call the initialise class to set the plugin up.
 */
new Initialise();
