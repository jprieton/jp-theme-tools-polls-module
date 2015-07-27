<?php

namespace jptt\core;

defined('ABSPATH') or die('No direct script access allowed');

class Poll_Schema {

	public static function create_termmeta_table() {
		global $wpdb;
		$wpdb instanceof wpdb;

		$charset = !empty($wpdb->charset) ?
						"DEFAULT CHARACTER SET {$wpdb->charset}" :
						'';

		$collate = !empty($wpdb->collate) ?
						"COLLATE {$wpdb->collate}" :
						'';

		$query = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}termmeta` ("
						. "`poll_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,"
						. "`post_id` bigint(20) unsigned NOT NULL DEFAULT '0',"
						. "`vote_ip` bigint(20) DEFAULT NULL,"
						. "`vote_value` longtext,"
						. "PRIMARY KEY (`poll_id`)"
						. ") ENGINE=InnoDB {$charset} {$collate} AUTO_INCREMENT=1";
		$wpdb->query($query);
	}

}
