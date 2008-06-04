<?php
/*
 *  tag.prunetags.php
 *  MDB: A media database
 *  Component: Tag - prunetags
 *  Remove tags not assigned to any title
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

 include_once('tag.taglist.php');

function prunetags()
{
	global $db,$tables;
	$taglist = taglist();
	foreach ($taglist as $tag) {
		if ($tag['count'] == 0) {
			$db->Execute("DELETE FROM " . $tables['tags'] . " WHERE id=" . $tag['id'] . " LIMIT 1");
		}
	}
}

?>
