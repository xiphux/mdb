<?php
/*
 *  user.useradd.php
 *  MDB: A media database
 *  Component: User - useradd
 *  Add a user
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

 include_once('display.message.php');

 function useradd($user,$pass,$admin)
 {
	global $mdb_conf,$tables, $cache;
	if (!(isset($_SESSION[$mdb_conf['session_key']]['user']) && ($_SESSION[$mdb_conf['session_key']]['user']['privilege'] > 0))) {
		message("You do not have access to this feature!","warning");
		return;
	}
	if (!$user) {
		message("No user given","warning");
		return;
	}
	if (!$pass) {
		message("No pass given","warning");
		return;
	}
	$testid = DBGetOne("SELECT * FROM " . $tables['users'] . " WHERE username=" . DBqstr($user,get_magic_quotes_gpc()));
	if ($testid) {
		message("User already exists","warning");
		return;
	}
	$q = "INSERT INTO " . $tables['users'] . " (username,password,privilege) VALUES (" . DBqstr($user,get_magic_quotes_gpc()) . "," . DBqstr(md5($pass)) . ",";
	if ($admin)
		$q .= "1";
	else
		$q .= "0";
	$q .= ")";
	DBExecute($q);
	$cache->del("userlist");
	$cache->del("userhistory");
 }

?>
