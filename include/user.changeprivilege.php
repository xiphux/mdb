<?php
/*
 *  user.changeprivilege.php
 *  MDB: A media database
 *  Component: User - changeprivilege
 *  Change a user's privilege
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

function changeprivilege($uid, $priv)
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
	if (!isset($priv)) {
		echo "No privilege specified";
		return;
	}
	if (($priv !== "1") && ($priv !== "0")) {
		echo "Invalid privilege specified";
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
	$q = "UPDATE " . $tables['users'] . " SET privilege=" . $db->qstr($priv) . " WHERE id=" . $uid . " LIMIT 1";
	$db->Execute($q);
}

?>
