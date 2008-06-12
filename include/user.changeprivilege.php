<?php
/*
 *  user.changeprivilege.php
 *  MDB: A media database
 *  Component: User - changeprivilege
 *  Change a user's privilege
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

 include_once('display.message.php');

function changeprivilege($uid, $priv)
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
	if (!isset($priv)) {
		message("No privilege specified","warning");
		return;
	}
	if (($priv !== "1") && ($priv !== "0")) {
		message("Invalid privilege specified","warning");
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
	$q = "UPDATE " . $tables['users'] . " SET privilege=" . $db->qstr($priv) . " WHERE id=" . $uid . " LIMIT 1";
	$db->Execute($q);
}

?>
