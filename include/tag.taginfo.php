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
	global $tables, $cache;
	if (!isset($id))
		return null;

	$tmp = $cache->Get("taginfo_" . $id);
	if ($tmp)
		return $tmp;

	$tag = DBGetRow("SELECT * FROM " . $tables['tags'] . " WHERE id=" . $id);
	if (!$tag)
		return null;
	$temp = DBGetArray("SELECT t1.* FROM " . $tables['titles'] . " AS t1, " . $tables['title_tag'] . " AS t2 WHERE t2.tag_id=" . $id . " AND t2.title_id=t1.id ORDER BY t1.title");
	if (sizeof($temp) > 0)
		$tag['titles'] = $temp;
	$tag['count'] = sizeof($temp);
	$cache->Set("taginfo_" . $id, $tag);
	return $tag;
}

?>
