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
add_filter('rwmb_meta_boxes', 'your_prefix_register_meta_boxes');

/**
 * Register meta boxes
 *
 * Remember to change "your_prefix" to actual prefix in your project
 *
 * @param array $meta_boxes List of meta boxes
 *
 * @return array
 */
function your_prefix_register_meta_boxes($meta_boxes) {
	$meta_boxes[] = array(
		'id' => 'poll-metabox',
		'title' => __('Poll options', 'jptt'),
		'post_types' => array('poll'),
		'context' => 'normal',
		'priority' => 'high',
		'autosave' => true,
		'fields' => array(
			array(
				'name' => __('Options', 'jptt'),
				'id' => "poll_options",
				'type' => 'text',
				'clone' => true,
			),
		),
	);

	return $meta_boxes;
}
