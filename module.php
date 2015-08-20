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
defined( 'ABSPATH' ) or die( 'No direct script access allowed' );

define( 'JPTT_POLL_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'JPTT_POLL_PLUGIN_URI', plugin_dir_url( __FILE__ ) );

include_once JPTT_POLL_PLUGIN_PATH . 'includes/class-poll.php';
include_once JPTT_POLL_PLUGIN_PATH . 'includes/register-post-type.php';
include_once JPTT_POLL_PLUGIN_PATH . 'includes/register-meta-boxes.php';

register_activation_hook( __FILE__, function() {
	jptt\Poll::create_schema();
} );

add_action( 'wp_ajax_user_poll_vote', function() {
	$Poll = new jptt\Poll();
	$Poll->vote();
}, 10 );

/**
 *
 * @param WP_Post $post
 */
function metabox_callback( $post = null ) {
	$poll_options = (array) get_post_meta( $post->ID, 'poll_options', TRUE );

	$total = jptt\Poll::get_total_votes( $post->ID );

	$format = '<div style="padding:5px">%s<div style="border: 1px solid darkgray; background-color: lightgray; padding: 5px 0; box-sizing: border-box; width:%s"></div></div>';
	foreach ( $poll_options as $key => $option ) {
		$count = jptt\Poll::get_total_votes( $post->ID, $key );
		$percent = ($count * 100) / 18;

		printf( $format, "{$option} ({$count})", $percent . '%' );
	}
}

function adding_custom_meta_boxes() {
	add_meta_box(
					'metabox-id', __( 'Votes', 'jptt' ), 'metabox_callback', 'poll', 'side', 'high'
	);
}

add_action( 'add_meta_boxes', 'adding_custom_meta_boxes', 10, 2 );

