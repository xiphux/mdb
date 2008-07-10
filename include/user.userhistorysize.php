<?php
/*
 *  user.userhistorysize.php
 *  MDB: A media database
 *  Component: User - userhistorysize
 *  Retrieves user's total amount downloaded
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

 include_once('display.message.php');

function userhistorysize($uid)
{
	global $tables,$mdb_conf;
	if (!isset($_SESSION[$mdb_conf['session_key']]['user'])) {
		message("You do not have access to this feature!","warning");
		return;
	}
	if (!isset($uid)) {
		message("No user specified!","warning");
		return;
	}

	$tmp = mdb_memcache_get("userhistorysize_" . $uid);
	if ($tmp)
		return $tmp;
	
	$ret = DBGetOne("SELECT SUM(fsize) FROM " . $tables['downloads'] . " WHERE uid=" . $uid);

	mdb_memcache_set("userhistorysize_" . $uid, $ret);

	return $ret;
}

?>
