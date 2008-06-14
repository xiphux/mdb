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
	global $mdb_conf,$db,$tables;
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
	$testid = $db->GetOne("SELECT id FROM " . $tables['users'] . " WHERE id=" . $uid . " LIMIT 1");
	if (!$testid) {
		message("No such user","warning");
		return;
	}
	$db->Execute("DELETE FROM " . $tables['users'] . " WHERE id=" . $uid . " LIMIT 1");
	$db->Execute("DELETE FROM " . $tables['downloads'] . " WHERE uid=" . $uid);
	$db->Execute("DELETE FROM " . $tables['preferences'] . " WHERE uid=" . $uid);
}

?>
