<?php
/*
 *  tag.deltag.php
 *  MDB: A media database
 *  Component: Tag - deltag
 *  Deletes a given tag
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

 include_once('display.message.php');

function deltag($tid)
{
	global $tables,$mdb_conf;
	if (!(isset($_SESSION[$mdb_conf['session_key']]['user']) && ($_SESSION[$mdb_conf['session_key']]['user']['privilege'] > 0))) {
		message("You do not have access to this feature!","warning");
		return;
	}
	if (!$tid) {
		message("No series specified","warning");
		return;
	}
	DBExecute("DELETE FROM " . $tables['tags'] . " WHERE id=" . $tid . " LIMIT 1");
	DBExecute("DELETE FROM " . $tables['title_tag'] . " WHERE tag_id=" . $tid);
}

?>
