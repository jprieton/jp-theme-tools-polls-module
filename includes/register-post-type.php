<?php
add_action( 'init',
	function () {
		$labels = array(
			'name'                => __( 'Polls', 'jptt' ),
			'singular_name'       => __( 'Poll', 'jptt' ),
			'menu_name'           => __( 'Polls', 'jptt' ),
			'name_admin_bar'      => __( 'Poll', 'jptt' ),
			'all_items'           => __( 'All Items', 'jptt' ),
			'search_items'        => __( 'Search Item', 'jptt' ),
		);
		$args = array(
			'label'               => __( 'poll', 'jptt' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'custom-fields', ),
			'taxonomies'          => array( ),
			'public'              => false,
			'show_ui'             => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-chart-line',
			'has_archive'         => false,
		);
		register_post_type( 'poll', $args );
	}
, 0 );
