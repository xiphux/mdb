<?php
/*
 *  user.userhistory.php
 *  MDB: A media database
 *  Component: User - userhistory
 *  Retrieves download history for local user
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

function userhistory($uid)
{
	global $db,$tables,$mdb_conf;
	if (!isset($_SESSION[$mdb_conf['session_key']]['user'])) {
		echo "You do not have access to this feature!";
		return;
	}
	if (!isset($uid)) {
		echo "No user specified!";
		return;
	}
	$ret = $db->GetArray("SELECT * FROM " . $tables['downloads'] . " WHERE uid=" . $uid . " ORDER BY time DESC");
	$size = count($ret);
	if ($size > 0) {
		for ($i = 0; $i < $size; $i++) {
			$ret2 = $db->GetRow("SELECT * FROM " . $tables['files'] . " WHERE id=" . $ret[$i]['fid'] . " LIMIT 1");
			if ($ret2)
				$ret[$i]['fileinfo'] = $ret2;
		}
	}
	return $ret;
}

?>
