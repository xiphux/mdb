<?php
/*
 *  user.getpref.php
 *  MDB: A media database
 *  Component: User - getpref
 *  Get a user's preference
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

function getpref($pref, $default)
{
	global $mdb_conf,$tables;
	if (!isset($_SESSION[$mdb_conf['session_key']]['user']))
		return $default;

	$tmp = mdb_memcache_get("pref_" . $_SESSION[$mdb_conf['session_key']]['user']['id'] . "_" . $pref);
	if ($tmp)
		return $tmp;

	$val = DBGetRow("SELECT * FROM " . $tables['preferences'] . " WHERE uid=" . $_SESSION[$mdb_conf['session_key']]['user']['id'] . " AND pref=" . DBqstr($pref));
	if ($val) {
		mdb_memcache_set("pref_" . $_SESSION[$mdb_conf['session_key']]['user']['id'] . "_" . $pref, $val['value']);
		return $val['value'];
	}
	return $default;
}

?>
