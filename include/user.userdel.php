<?php
/*
 *  user.userdel.php
 *  MDB: A media database
 *  Component: User - userdel
 *  Delete a given user
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

 include_once('display.message.php');

function userdel($uid)
{
	global $mdb_conf,$tables, $cache;
	if (!(isset($_SESSION[$mdb_conf['session_key']]['user']) && ($_SESSION[$mdb_conf['session_key']]['user']['privilege'] > 0))) {
		message("You do not have access to this feature!","warning");
		return;
	}
	if (!$uid) {
		message("No user specified","warning");
		return;
	}
	if ($_SESSION[$mdb_conf['session_key']]['user']['id'] == $uid) {
		message("You are this user","warning");
		return;
	}
	$testid = DBGetOne("SELECT id FROM " . $tables['users'] . " WHERE id=" . $uid);
	if (!$testid) {
		message("No such user","warning");
		return;
	}
	DBExecute("DELETE FROM " . $tables['users'] . " WHERE id=" . $uid);
	DBExecute("DELETE FROM " . $tables['downloads'] . " WHERE uid=" . $uid);
	DBExecute("DELETE FROM " . $tables['preferences'] . " WHERE uid=" . $uid);
	$cache->del("userlist");
	$cache->del("userhistory");
	$cache->del("userhistory_" . $uid);
	$cache->del("userhistorysize_" . $uid);
}

?>
