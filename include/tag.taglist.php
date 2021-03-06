<?php
/*
 *  tag.taglist.php
 *  MDB: A media database
 *  Component: Tag - taglist
 *  Returns list of tags with counts
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

function taglist()
{
	global $tables, $cache;

	$tmp = $cache->Get("taglist");
	if ($tmp)
		return $tmp;
	
	$q = "SELECT " . $tables['tags'] . ".*, COUNT(" . $tables['title_tag'] . ".title_id) AS count FROM " . $tables['tags'] . " LEFT JOIN " . $tables['title_tag'] . " ON " . $tables['tags'] . ".id = " . $tables['title_tag'] . ".tag_id GROUP BY tag ORDER BY tag";
	$tags = DBGetArray($q);

	$cache->Set("taglist",$tags);

	return $tags;
}

?>
