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
	DBExecute("DELETE FROM " . $tables['tags'] . " WHERE id=" . $tid);
	$titles = DBGetArray("SELECT title_id FROM " . $tables['title_tag'] . " WHERE tag_id=" . $tid);
	DBExecute("DELETE FROM " . $tables['title_tag'] . " WHERE tag_id=" . $tid);
	foreach ($titles as $ti) {
		mdb_memcache_delete("tid" . $ti['title_id']);
		mdb_memcache_delete("output_title_" . $ti['title_id']);
		mdb_memcache_delete("output_title_" . $ti['title_id'] . "_user");
		mdb_memcache_delete("output_title_" . $ti['title_id'] . "_user_dl");
	}
	mdb_memcache_delete("taglist");
	mdb_memcache_delete("output_tagcloud");
	mdb_memcache_delete("taginfo_" . $tid);
	mdb_memcache_delete("output_tag_" . $tid);
	mdb_memcache_delete("output_tag_" . $tid . "_priv");
}

?>
