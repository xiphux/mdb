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
	global $tables,$mdb_conf, $cache;
	if (!(isset($_SESSION[$mdb_conf['session_key']]['user']) && ($_SESSION[$mdb_conf['session_key']]['user']['privilege'] > 0))) {
		message("You do not have access to this feature!","warning");
		return;
	}
	if (!$tid) {
		message("No series specified","warning");
		return;
	}
	DBExecute("DELETE FROM " . $tables['tags'] . " WHERE id=" . $tid);
	$titles = DBGetArray("SELECT title_id FROM " . $tables['title_tag'] . " WHERE tag_id=" . $tid);
	DBExecute("DELETE FROM " . $tables['title_tag'] . " WHERE tag_id=" . $tid);
	foreach ($titles as $ti) {
		$cache->del("tid" . $ti['title_id']);
		$cache->del("output_title_" . $ti['title_id']);
		$cache->del("output_title_" . $ti['title_id'] . "_user");
		$cache->del("output_title_" . $ti['title_id'] . "_user_dl");
	}
	$cache->del("taglist");
	$cache->del("output_tagcloud");
	$cache->del("taginfo_" . $tid);
	$cache->del("output_tag_" . $tid);
	$cache->del("output_tag_" . $tid . "_priv");
}

?>
