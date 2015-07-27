<?php

/**
 * Plugin Name: JP Theme Tools Poll Module
 * Plugin URI: https://github.com/jprieton/jp-theme-tools-polls-module/
 * Description: Poll module for JP Theme Tools
 * Version: 0.1.0
 * Author: Javier Prieto
 * Text Domain: jptt
 * Domain Path: /languages
 * Author URI: https://github.com/jprieton/
 * License: GPL2
 */
defined('ABSPATH') or die('No direct script access allowed');

define('JPTT_POLL_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('JPTT_POLL_PLUGIN_URI', plugin_dir_url(__FILE__));

include_once JPTT_POLL_PLUGIN_PATH . 'includes/register-post-type.php';
include_once JPTT_POLL_PLUGIN_PATH . 'includes/register-meta-boxes.php';

register_activation_hook(__FILE__, function() {
	include_once JPTT_POLL_PLUGIN_PATH . 'includes/class-poll-schema.php';
	jptt\poll\Schema::create_poll_schema();
});
