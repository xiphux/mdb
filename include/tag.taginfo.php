<?php
/*
 *  tag.taginfo.php
 *  MDB: A media database
 *  Component: Tag - taginfo
 *  Fetches all info on a given tag
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

function taginfo($id)
{
	global $db,$tables;
	if (!isset($id))
		return null;
	$tag = $db->GetRow("SELECT * FROM " . $tables['tags'] . " WHERE id=" . $id . " LIMIT 1");
	if (!$tag)
		return null;
	$temp = $db->GetArray("SELECT t1.* FROM " . $tables['titles'] . " AS t1, " . $tables['title_tag'] . " AS t2 WHERE t2.tag_id=" . $id . " AND t2.title_id=t1.id ORDER BY t1.title");
	if (sizeof($temp) > 0)
		$tag['titles'] = $temp;
	$tag['count'] = sizeof($temp);
	return $tag;
}

?>
