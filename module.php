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

/**
 *  Load plugin textdomain.
 */
add_action( 'plugins_loaded', function () {
	load_plugin_textdomain( 'jptt', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
} );

register_activation_hook( __FILE__, function() {
	jptt\Poll::create_schema();
} );

add_filter( 'rwmb_meta_boxes', function($meta_boxes) {
	$meta_boxes[] = array(
			'id' => 'poll-metabox',
			'title' => __( 'Poll options', 'jptt' ),
			'post_types' => array( 'poll' ),
			'context' => 'normal',
			'priority' => 'high',
			'autosave' => true,
			'fields' => array(
					array(
							'name' => __( 'Options', 'jptt' ),
							'id' => "_poll_options",
							'type' => 'text',
							'clone' => true,
					),
			),
	);
	return $meta_boxes;
} );

add_action( 'add_meta_boxes', function() {
	add_meta_box( 'metabox-id', __( 'Votes', 'jptt' ), function( $post = null ) {
		$poll_options = (array) get_post_meta( $post->ID, '_poll_options', TRUE );

		$total = jptt\Poll::get_total_votes( $post->ID );

		$format = '<div style="padding:5px">%s<div style="border: 1px solid darkgray; background-color: lightgray; box-sizing: border-box; width:%s">%s</div></div>';
		foreach ( $poll_options as $key => $option ) {
			$count = jptt\Poll::get_total_votes( $post->ID, $key );
			$percent = ($count * 100) / $total;

			printf( $format, $option, $percent . '%', number_format( $percent, 2 ) . '%' );
		}
	}, 'poll', 'side', 'high' );
}, 10, 2 );

add_action( 'wp_ajax_user_poll_vote', function() {
	$Poll = new jptt\Poll();
	$Poll->vote();
	$result = $Poll->vote();
	do_action('user_after_vote', $result);
}, 10 );

add_action( 'wp_ajax_nopriv_user_poll_vote', function() {
	$Poll = new jptt\Poll();
	$result = $Poll->vote();
	do_action('user_after_vote', $result);
}, 10 );
