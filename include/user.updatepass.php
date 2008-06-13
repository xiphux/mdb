<?php
/*
 *  user.updatepass.php
 *  MDB: A media database
 *  Component: User - updatepass
 *  Update a user's password
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

 include_once('display.message.php');

function updatepass($oldpass,$newpass,$newpass2)
{
	global $mdb_conf,$db,$tables;
 	if (!isset($_SESSION[$mdb_conf['session_key']]['user'])) {
		message("Not valid for anonymous users!","warning");
		return;
	}
	if (!$oldpass) {
		message("No old password specified","warning");
		return;
	}
	if (!$newpass) {
		message("No new password specified","warning");
		return;
	}
	if (!$newpass2) {
		message("No password repeat specified","warning");
		return;
	}
	$user = $db->GetRow("SELECT * FROM " . $tables['users'] . " WHERE id=" . $_SESSION[$mdb_conf['session_key']]['user']['id'] . " LIMIT 1");
	if (!$user) {
		message("User not found","warning");
		return;
	}
	if (md5($oldpass) != $user['password']) {
		message("Old password is incorrect","warning");
		return;
	}
	if ($newpass !== $newpass2) {
		message("New password fields do not match","warning");
		return;
	}
	$q = "UPDATE " . $tables['users'] . " SET password=" . $db->qstr(md5($newpass)) . " WHERE id=" . $user['id'] . " LIMIT 1";
	if ($db->Execute($q))
		message("Password changed successfully");
	else
		message("Password change failed","warning");
}

?>