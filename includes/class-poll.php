<?php

namespace jptt;

defined( 'ABSPATH' ) or die( 'No direct script access allowed' );

class Poll {

	public static function create_schema() {
		global $wpdb;
		$wpdb instanceof \wpdb;

		$charset = !empty( $wpdb->charset ) ?
						"DEFAULT CHARACTER SET {$wpdb->charset}" :
						'';

		$collate = !empty( $wpdb->collate ) ?
						"COLLATE {$wpdb->collate}" :
						'';

		$query = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}polls` ("
						. "`poll_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,"
						. "`post_id` bigint(20) unsigned NOT NULL DEFAULT '0',"
						. "`vote_ip` bigint(20) DEFAULT NULL,"
						. "`vote_value` bigint(20),"
						. "`vote_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,"
						. "PRIMARY KEY (`poll_id`),"
						. "KEY `post_id` (`post_id`)"
						. ") ENGINE=InnoDB {$charset} {$collate} AUTO_INCREMENT=1";
		$wpdb->query( $query );
	}

	public function vote() {
		global $wpdb;
		$wpdb instanceof \wpdb;
		$Input = new core\Input();

		$post_id = (int) $Input->post( 'poll_id' );
		if ( $post_id === 0 ) {
			return FALSE;
		}
		$poll_options = (array) get_post_meta( $post_id, '_poll_options', TRUE );
		$poll_keys = array_keys( $poll_options );

		$vote_value = (int) $Input->post( 'poll_vote', FILTER_SANITIZE_NUMBER_INT );

		if ( !in_array( $vote_value, $poll_keys ) || !is_numeric( $vote_value ) ) {
			return FALSE;
		}

		if ( !empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$vote_ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$vote_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$vote_ip = $_SERVER['REMOTE_ADDR'];
		}

		if ( !filter_var( $vote_ip, FILTER_VALIDATE_IP ) ) {
			return FALSE;
		}

		$vote_ip = ( $vote_ip == '::1' ) ? ip2long( '127.0.0.1' ) : ip2long( $vote_ip );

		$voted = $wpdb->get_var( "SELECT * FROM `{$wpdb->prefix}polls` WHERE post_id = '{$post_id}' AND vote_ip = '{$vote_ip}' LIMIT 1" );

		$this->update_post_meta( $post_id );

		if ( empty( $voted ) ) {
			$wpdb->insert( "{$wpdb->prefix}polls", compact( 'post_id', 'vote_ip', 'vote_value' ) );
		}
	}

	private function update_post_meta( $post_id ) {

		$options = get_post_meta($post_id, '_poll_options', TRUE);

		$post_meta = array();
		foreach ($options as $key => $value) {
			$post_meta[$key] = (object) array(
					'vote_option' => $key,
					'vote_count' => self::get_total_votes( $post_id, $key )
			);
		}

		update_post_meta($post_id, '_poll_votes', $post_meta);
	}

	/**
	 *
	 * @global wpdb $wpdb
	 * @param int $post_id
	 * @param int $vote_value
	 * @return int
	 */
	public static function get_total_votes( $post_id, $vote_value = NULL ) {
		global $wpdb;
		$query = "SELECT count(*) as total FROM `{$wpdb->prefix}polls` WHERE post_id = '{$post_id}'";

		if ( is_numeric( $vote_value ) ) {
			$query .= " AND vote_value = {$vote_value}";
		}
		return (int) $wpdb->get_var( $query );
	}

}
