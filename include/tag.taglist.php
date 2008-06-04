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
	global $db,$tables;
	$tags = $db->GetArray("SELECT * FROM " . $tables['tags'] . " ORDER BY tag");
	$size = count($tags);
	for ($i = 0; $i < $size; $i++)
		$tags[$i]['count'] = $db->GetOne("SELECT COUNT(title_id) FROM " . $tables['title_tag'] . " WHERE tag_id=" . $tags[$i]['id']);
	return $tags;
}

?>
