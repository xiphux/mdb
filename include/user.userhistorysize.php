<?php
/*
 *  user.userhistorysize.php
 *  MDB: A media database
 *  Component: User - userhistorysize
 *  Retrieves user's total amount downloaded
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

function userhistorysize($uid)
{
	global $db,$tables,$mdb_conf;
	if (!isset($_SESSION[$mdb_conf['session_key']]['user'])) {
		echo "You do not have access to this feature!";
		return;
	}
	if (!isset($uid)) {
		echo "No user specified!";
		return;
	}
	return $db->GetOne("SELECT SUM(fsize) FROM " . $tables['downloads'] . " WHERE uid=" . $uid);
}

?>
