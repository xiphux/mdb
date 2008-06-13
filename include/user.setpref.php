<?php
/*
 *  user.setpref.php
 *  MDB: A media database
 *  Component: User - setpref
 *  Set a user's preference
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

 include_once('display.message.php');

 function setpref($pref, $value)
 {
	global $mdb_conf,$db,$tables;
	if (!isset($_SESSION[$mdb_conf['session_key']]['user'])) {
		message("Not valid for anonymous users","warning");
		return;
	}
	$val = $db->GetRow("SELECT * FROM " . $tables['preferences'] . " WHERE uid=" . $_SESSION[$mdb_conf['session_key']]['user']['id'] . " AND pref=" . $db->qstr($pref));
	if ($val) {
		$db->Execute("UPDATE " . $tables['preferences'] . " SET value=" . $db->qstr($value) . " WHERE id=" . $val['id'] . " AND uid=" . $_SESSION[$mdb_conf['session_key']]['user']['id'] . " AND pref=" . $db->qstr($pref) . " LIMIT 1");
	} else {
		$db->Execute("INSERT INTO " . $tables['preferences'] . " (uid,pref,value) VALUES (" . $_SESSION[$mdb_conf['session_key']]['user']['id'] . "," . $db->qstr($pref) . "," . $db->qstr($value) . ")");
	}
 }

?>
