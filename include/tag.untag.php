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

function untag($tid, $tag)
{
	global $db,$tables,$mdb_conf;
	if (!isset($_SESSION[$mdb_conf['session_key']]['user'])) {
		echo "You do not have access to this feature!";
		return;
	}
	if (!$tid) {
		echo "No series specified";
		return;
	}
	if (!$tag) {
		echo "No tag specified";
		return;
	}
	$db->Execute("DELETE FROM " . $tables['title_tag'] . " WHERE title_id=" . $tid . " AND tag_id=" . $tag);
	prunetags();
}

?>
