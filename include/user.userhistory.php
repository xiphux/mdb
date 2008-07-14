<?php
/*
 *  user.userhistory.php
 *  MDB: A media database
 *  Component: User - userhistory
 *  Retrieves download history for local user
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

 include_once('display.message.php');

function userhistory($uid)
{
	global $tables,$mdb_conf, $cache;
	if (!isset($_SESSION[$mdb_conf['session_key']]['user'])) {
		message("You do not have access to this feature!","warning");
		return;
	}
	if (!isset($uid)) {
		message("No user specified!","warning");
		return;
	}

	$tmp = $cache->get("userhistory_" . $uid);
	if ($tmp)
		return $tmp;

	$q = "SELECT " . $tables['downloads'] . ".*, " . $tables['files'] . ".id AS file_exists FROM " . $tables['downloads'] . " LEFT JOIN " . $tables['files'] . " ON (" . $tables['downloads'] . ".fid = " . $tables['files'] . ".id AND " . $tables['downloads'] . ".file = " . $tables['files'] . ".file) WHERE uid=" . $uid . " ORDER BY " . $tables['downloads'] . ".time DESC";
	$ret = DBGetArray($q);

	$cache->set("userhistory_" . $uid, $ret);

	return $ret;
}

?>
