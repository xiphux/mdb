<?php
/*
 *  user.userdel.php
 *  MDB: A media database
 *  Component: User - userdel
 *  Delete a given user
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

function userdel($uid)
{
	global $mdb_conf,$db,$tables;
	if (!(isset($_SESSION[$mdb_conf['session_key']]['user']) && ($_SESSION[$mdb_conf['session_key']]['user']['privilege'] > 0))) {
		echo "You do not have access to this feature!";
		return;
	}
	if (!$uid) {
		echo "No user specified";
		return;
	}
	if ($_SESSION[$mdb_conf['session_key']]['user']['id'] == $uid) {
		echo "You are this user";
		return;
	}
	$testid = $db->GetOne("SELECT id FROM " . $tables['users'] . " WHERE id=" . $uid . " LIMIT 1");
	if (!$testid) {
		echo "No such user";
		return;
	}
	$db->Execute("DELETE FROM " . $tables['users'] . " WHERE id=" . $uid . " LIMIT 1");
	$db->Execute("DELETE FROM " . $tables['downloads'] . " WHERE uid=" . $uid);
}

?>
