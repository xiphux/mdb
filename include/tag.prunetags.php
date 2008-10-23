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
	global $tables, $cache;
	$q = "DELETE tmp.* FROM " . $tables['tags'] . " AS tmp INNER JOIN (SELECT " . $tables['tags'] . ".id FROM " . $tables['tags'] . " LEFT JOIN " . $tables['title_tag'] . " ON " . $tables['tags'] . ".id = " . $tables['title_tag'] . ".tag_id GROUP BY " . $tables['tags'] . ".tag HAVING (COUNT(" . $tables['title_tag'] . ".title_id) = 0)) AS tmp2 ON tmp2.id = tmp.id";
	DBExecute($q);
	$cache->Del("taglist");
	$cache->Del("output_tagcloud");
}

?>
