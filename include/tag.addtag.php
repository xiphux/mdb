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
	global $db,$tables,$mdb_conf;
	if (!isset($_SESSION[$mdb_conf['session_key']]['user'])) {
		message("You do not have access to this feature!","warning");
		return;
	}
	if (!$tid) {
		message("No series specified","warning");
		return;
	}
	$tag = trim($tag);
	if (!(isset($tag) && (strlen($tag) > 0))) {
		message("No tag specified","warning");
		return;
	}
	$id = $db->GetOne("SELECT id FROM " . $tables['tags'] . " WHERE UPPER(tag) LIKE '%" . strtoupper($tag) . "%' LIMIT 1");
	if (!$id) {
		$db->Execute("INSERT INTO " . $tables['tags'] . " (tag) VALUES (" . $db->qstr(strtolower($tag)) . ")");
		$id = $db->_insertid();
	}
	$db->Execute("INSERT IGNORE INTO " . $tables['title_tag'] . " (title_id,tag_id) VALUES (" . $tid . "," . $id . ")");
	return;
}

?>
