<?php
/*
 *  user.login.php
 *  MDB: A media database
 *  Component: User - login
 *  Tests a given user/pass for validity and sets the session key
 *  if legitimate
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

 function login($user,$pass)
 {
 	global $mdb_conf,$db,$tables;
	if (!(isset($user) && strlen($user) > 0)) {
		echo "No username entered";
		return;
	}
	if (!(isset($pass) && strlen($pass) > 0)) {
		echo "No password entered";
		return;
	}
	$u = $db->GetRow("SELECT * FROM " . $tables['users'] . " WHERE username=" . $db->qstr($user) . " LIMIT 1");
	if (!$u) {
		echo "No such user";
		return;
	}
	if (md5($pass) !== $u['password']) {
		echo "Incorrect password";
		return;
	}
	$_SESSION[$mdb_conf['session_key']]['user'] = $u;
	echo "Logged in successfully";
 }

?>
