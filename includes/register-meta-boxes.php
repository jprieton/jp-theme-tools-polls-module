<?php

/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 * Please read them CAREFULLY.
 *
 * You also should read the changelog to know what has been changed before updating.
 *
 * For more information, please visit:
 * @link http://metabox.io/docs/registering-meta-boxes/
 */
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
							'id' => "poll_options",
							'type' => 'text',
							'clone' => true,
					),
			),
	);

	return $meta_boxes;
} );

add_action( 'add_meta_boxes', function() {
	add_meta_box( 'metabox-id', __( 'Votes', 'jptt' ), function( $post = null ) {
		$poll_options = (array) get_post_meta( $post->ID, 'poll_options', TRUE );

		$total = jptt\Poll::get_total_votes( $post->ID );

		$format = '<div style="padding:5px">%s<div style="border: 1px solid darkgray; background-color: lightgray; box-sizing: border-box; width:%s">%s</div></div>';
		foreach ( $poll_options as $key => $option ) {
			$count = jptt\Poll::get_total_votes( $post->ID, $key );
			$percent = ($count * 100) / $total;

			printf( $format, $option, $percent . '%', number_format( $percent, 2 ) . '%' );
		}
	}, 'poll', 'side', 'high' );
}, 10, 2 );

