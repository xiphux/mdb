<?php
/*
 *  display.cacheflush.php
 *  MDB: A media database
 *  Component: Display - cacheflush
 *  Flush cache
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

include_once('include/display.message.php');

function cacheflush()
{
	global $mdb_conf, $memcached;
	if (!(isset($_SESSION[$mdb_conf['session_key']]['user']) && ($_SESSION[$mdb_conf['session_key']]['user']['privilege'] > 0))) {
		message("You do not have access to this feature!","warning");
		return;
	}

	if (mdb_memcache_flush() === TRUE)
		message("Cache flushed","highlight");
	else
		message("Could not flush cache","warning");
}

?>
