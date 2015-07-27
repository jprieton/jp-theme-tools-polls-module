<?php

defined('ABSPATH') or die('No direct script access allowed');

function add_poll_vote() {
	include_once JPTT_PLUGIN_PATH . 'core/class-input.php';

	$Input =  new jptt\core\Input();


}

add_action('wp_ajax_poll_vote', 'add_poll_vote');