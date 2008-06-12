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
	global $db,$tables,$mdb_conf;
	if (!isset($_SESSION[$mdb_conf['session_key']]['user'])) {
		message("You do not have access to this feature!","warning");
		return;
	}
	if (!isset($uid)) {
		message("No user specified!","warning");
		return;
	}
	return $db->GetOne("SELECT SUM(fsize) FROM " . $tables['downloads'] . " WHERE uid=" . $uid);
}

?>
