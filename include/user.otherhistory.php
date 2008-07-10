<?php
/*
 *  user.otherhistory.php
 *  MDB: A media database
 *  Component: User - otherhistory
 *  Retrieves download history for users other than local
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

 include_once('display.message.php');
 include_once('user.userhistory.php');
 include_once('user.userhistorysize.php');

function otherhistory()
{
	global $tables,$mdb_conf;
	if (!(isset($_SESSION[$mdb_conf['session_key']]['user']) && ($_SESSION[$mdb_conf['session_key']]['user']['privilege'] > 0))) {
		message("You do not have access to this feature!","warning");
		return;
	}

	$u = mdb_memcache_get("userlist_not_" . $_SESSION[$mdb_conf['session_key']]['user']['id']);
	if (!$u) {
		$u = DBGetArray("SELECT * FROM " . $tables['users'] . " WHERE id!=" . $_SESSION[$mdb_conf['session_key']]['user']['id'] . " ORDER BY username");
		if ($u)
			mdb_memcache_set("userlist_not_" . $_SESSION[$mdb_conf['session_key']]['user']['id'], $u);
	}
	$size = count($u);
	for ($i = 0; $i < $size; $i++) {
		$ret2 = userhistory($u[$i]['id']);
		if ($ret2) {
			$u[$i]['downloads'] = $ret2;
			$u[$i]['total'] = userhistorysize($u[$i]['id']);
		}
	}
	return $u;
}

?>
