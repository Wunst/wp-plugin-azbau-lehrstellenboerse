<?php

/*
 * Plugin Name: Lehrstellenbörse
 * Plugin URI: https://github.com/Wunst/wp-plugin-azbau-lehrstellenboerse
 * Author: Ben Matthies
 * Author URI: https://github.com/Wunst
 * Version: 1.2.0
 * Update URI: false
 * GitHub Plugin URI: Wunst/wp-plugin-azbau-lehrstellenboerse
 * Primary Branch: main
 * Release Asset: true
 * Requires at least: 6.2
 * Requires PHP: 8.2
 */

define( "LSB_PLUGDIR", plugin_dir_path(__FILE__) );
define( "LSB_PLUGURL", plugin_dir_url(__FILE__) );

require_once __DIR__ . '/vendor/autoload.php';

require_once(LSB_PLUGDIR . "/includes/admin.php");
require_once(LSB_PLUGDIR . "/includes/display.php");

