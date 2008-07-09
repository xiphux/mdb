<?php
/*
 *  tag.addtag.php
 *  MDB: A media database
 *  Component: Tag - addtag
 *  Adds a tag to a title (creating new tag if necessary)
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

 include_once('display.message.php');

function addtag($tid,$tag)
{
	global $tables,$mdb_conf;
	if (!isset($_SESSION[$mdb_conf['session_key']]['user'])) {
		message("You do not have access to this feature!","warning");
		return;
	}
	if (!$tid) {
		message("No series specified","warning");
		return;
	}
	$tag = preg_replace("/[^a-z0-9]/i","",$tag);
	if (!(isset($tag) && (strlen($tag) > 0))) {
		message("No tag specified","warning");
		return;
	}
	$id = DBGetOne("SELECT id FROM " . $tables['tags'] . " WHERE UPPER(tag)='" . strtoupper($tag) . "'");
	if (!$id) {
		DBExecute("INSERT INTO " . $tables['tags'] . " (tag) VALUES (" . DBqstr(strtolower($tag),get_magic_quotes_gpc()) . ")");
		$id = DBInsertID();
		if ($id === false)
			$id = DBGetOne("SELECT id FROM " . $tables['tags'] . " WHERE UPPER(tag)='" . strtoupper($tag) . "'");
	}
	DBExecute("INSERT IGNORE INTO " . $tables['title_tag'] . " (title_id,tag_id) VALUES (" . $tid . "," . $id . ")");

	mdb_memcache_delete("tid" . $tid);

	return;
}

?>
