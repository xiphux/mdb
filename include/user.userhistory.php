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
	global $db,$tables,$mdb_conf;
	if (!isset($_SESSION[$mdb_conf['session_key']]['user'])) {
		message("You do not have access to this feature!","warning");
		return;
	}
	if (!isset($uid)) {
		message("No user specified!","warning");
		return;
	}
	$q = "SELECT " . $tables['downloads'] . ".*, " . $tables['files'] . ".id AS file_exists FROM " . $tables['downloads'] . " LEFT JOIN " . $tables['files'] . " ON (" . $tables['downloads'] . ".fid = " . $tables['files'] . ".id AND " . $tables['downloads'] . ".file = " . $tables['files'] . ".file) WHERE uid=" . $uid . " ORDER BY " . $tables['downloads'] . ".time DESC";
	$ret = $db->GetArray($q);
	return $ret;
}

?>
