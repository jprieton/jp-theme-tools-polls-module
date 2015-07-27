<?php

namespace jptt\poll;

defined('ABSPATH') or die('No direct script access allowed');

class Schema {

	public static function create_poll_schema() {
		global $wpdb;
		$wpdb instanceof \wpdb;

		$charset = !empty($wpdb->charset) ?
						"DEFAULT CHARACTER SET {$wpdb->charset}" :
						'';

		$collate = !empty($wpdb->collate) ?
						"COLLATE {$wpdb->collate}" :
						'';

		$query = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}polls` ("
						. "`poll_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,"
						. "`post_id` bigint(20) unsigned NOT NULL DEFAULT '0',"
						. "`vote_ip` bigint(20) DEFAULT NULL,"
						. "`vote_value` longtext,"
						. "`vote_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,"
						. "PRIMARY KEY (`poll_id`),"
						. "KEY `post_id` (`post_id`)"
						. ") ENGINE=InnoDB {$charset} {$collate} AUTO_INCREMENT=1";
		$wpdb->query($query);
	}

}
