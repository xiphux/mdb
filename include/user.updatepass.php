<?php
/*
 *  user.updatepass.php
 *  MDB: A media database
 *  Component: User - updatepass
 *  Update a user's password
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

function updatepass($oldpass,$newpass,$newpass2)
{
	global $mdb_conf,$db,$tables;
 	if (!isset($_SESSION[$mdb_conf['session_key']]['user'])) {
		echo "Not valid for anonymous users!";
		return;
	}
	if (!$oldpass) {
		echo "No old password specified";
		return;
	}
	if (!$newpass) {
		echo "No new password specified";
		return;
	}
	if (!$newpass2) {
		echo "No password repeat specified";
		return;
	}
	$user = $db->GetRow("SELECT * FROM " . $tables['users'] . " WHERE id=" . $_SESSION[$mdb_conf['session_key']]['user']['id'] . " LIMIT 1");
	if (!$user) {
		echo "Error: user not found";
		return;
	}
	if (md5($oldpass) != $user['password']) {
		echo "Old password is incorrect";
		return;
	}
	if ($newpass !== $newpass2) {
		echo "New password fields do not match";
		return;
	}
	$q = "UPDATE " . $tables['users'] . " SET password=" . $db->qstr(md5($newpass)) . " WHERE id=" . $user['id'] . " LIMIT 1";
	if ($db->Execute($q))
		echo "Password changed successfully";
	else
		echo "Password change failed";
}

?>
