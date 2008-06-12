<?php
/*
 *  user.useradd.php
 *  MDB: A media database
 *  Component: User - userdel
 *  Add a user
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

 function useradd($user,$pass,$admin)
 {
	global $mdb_conf,$db,$tables;
	if (!(isset($_SESSION[$mdb_conf['session_key']]['user']) && ($_SESSION[$mdb_conf['session_key']]['user']['privilege'] > 0))) {
		echo "You do not have access to this feature!";
		return;
	}
	if (!$user) {
		echo "No user given";
		return;
	}
	if (!$pass) {
		echo "No pass given";
		return;
	}
	$testid = $db->GetOne("SELECT * FROM " . $tables['users'] . " WHERE username=" . $db->qstr($user) . " LIMIT 1");
	if ($testid) {
		echo "User already exists";
		return;
	}
	$q = "INSERT INTO " . $tables['users'] . " (username,password,privilege) VALUES (" . $db->qstr($user) . "," . $db->qstr(md5($pass)) . ",";
	if ($admin)
		$q .= "1";
	else
		$q .= "0";
	$q .= ")";
	$db->Execute($q);
 }

?>
