<?php
/*
 *  tag.untag.php
 *  MDB: A media database
 *  Component: Tag - untag
 *  Removes a tag from a title
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

 include_once('tag.prunetags.php');
 include_once('display.message.php');

function untag($tid, $tag)
{
	global $tables,$mdb_conf, $cache;
	if (!isset($_SESSION[$mdb_conf['session_key']]['user'])) {
		message("You do not have access to this feature!","warning");
		return;
	}
	if (!$tid) {
		message("No series specified","warning");
		return;
	}
	if (!$tag) {
		message("No tag specified","warning");
		return;
	}
	DBExecute("DELETE FROM " . $tables['title_tag'] . " WHERE title_id=" . $tid . " AND tag_id=" . $tag);
	$cache->Del("tid" . $tid);
	$cache->Del("taginfo_" . $tag);
	$cache->Del("output_tag_" . $tag);
	$cache->Del("output_tag_" . $tag . "_priv");
	$cache->Del("output_title_" . $tid);
	$cache->Del("output_title_" . $tid . "_user");
	$cache->Del("output_title_" . $tid . "_user_dl");
	prunetags();
}

?>
