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
	global $mdb_conf,$tables, $cache;
	if (!isset($_SESSION[$mdb_conf['session_key']]['user'])) {
		message("Not valid for anonymous users","warning");
		return;
	}
	$val = DBGetRow("SELECT * FROM " . $tables['preferences'] . " WHERE uid=" . $_SESSION[$mdb_conf['session_key']]['user']['id'] . " AND pref=" . DBqstr($pref));
	if ($val) {
		DBExecute("UPDATE " . $tables['preferences'] . " SET value=" . DBqstr($value) . " WHERE id=" . $val['id'] . " AND uid=" . $_SESSION[$mdb_conf['session_key']]['user']['id'] . " AND pref=" . DBqstr($pref));
	} else {
		DBExecute("INSERT INTO " . $tables['preferences'] . " (uid,pref,value) VALUES (" . $_SESSION[$mdb_conf['session_key']]['user']['id'] . "," . DBqstr($pref) . "," . DBqstr($value) . ")");
	}
	$cache->Del("pref_" . $_SESSION[$mdb_conf['session_key']]['user']['id'] . "_" . $pref);

 }

?>
