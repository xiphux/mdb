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
	global $mdb_conf,$db,$tables;
	if (!isset($_SESSION[$mdb_conf['session_key']]['user']))
		return $default;
	$val = $db->GetRow("SELECT * FROM " . $tables['preferences'] . " WHERE uid=" . $_SESSION[$mdb_conf['session_key']]['user']['id'] . " AND pref=" . $db->qstr($pref));
	if ($val)
		return $val['value'];
	return $default;
}

?>
