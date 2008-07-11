<?php
/*
 *  display.database.php
 *  MDB: A media database
 *  Component: Display - database
 *  Database operations
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

 include_once('display.message.php');

 function database()
 {
	global $mdb_conf, $tpl;
	if (!(isset($_SESSION[$mdb_conf['session_key']]['user']) && ($_SESSION[$mdb_conf['session_key']]['user']['privilege'] > 0))) {
		message("You do not have access to this feature!","warning");
		return;
	}

	$out = mdb_memcache_get("output_database");
	if (!$out) {
		$tpl->clear_all_assign();
		$out = $tpl->fetch("database.tpl");
		mdb_memcache_set("output_database", $out);
	}

	echo $out;
 }

?>
