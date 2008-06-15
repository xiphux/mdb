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

 include_once('display.message.php');

 function login($user,$pass)
 {
 	global $mdb_conf,$tables;
	if (!(isset($user) && strlen($user) > 0)) {
		message("No username entered","warning");
		return;
	}
	if (!(isset($pass) && strlen($pass) > 0)) {
		message("No password entered","warning");
		return;
	}
	$u = DBGetRow("SELECT * FROM " . $tables['users'] . " WHERE username=" . DBqstr($user,get_magic_quotes_gpc()) . " LIMIT 1");
	if (!$u) {
		message("No such user","warning");
		return;
	}
	if (md5($pass) !== $u['password']) {
		message("Incorrect password","warning");
		return;
	}
	unset($u['password']);
	$_SESSION[$mdb_conf['session_key']]['user'] = $u;
	message("Logged in successfully");
 }

?>
