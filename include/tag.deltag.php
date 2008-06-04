<?php
/*
 *  tag.deltag.php
 *  MDB: A media database
 *  Component: Tag - deltag
 *  Deletes a given tag
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

function deltag($tid)
{
	global $db,$tables,$mdb_conf;
	if (!(isset($_SESSION[$mdb_conf['session_key']]['user']) && ($_SESSION[$mdb_conf['session_key']]['user']['privilege'] > 0))) {
		echo "You do not have access to this feature!";
		return;
	}
	if (!$tid) {
		echo "No series specified";
		return;
	}
	$db->Execute("DELETE FROM " . $tables['tags'] . " WHERE id=" . $tid . " LIMIT 1");
	$db->Execute("DELETE FROM " . $tables['title_tag'] . " WHERE tag_id=" . $tid);
}

?>
