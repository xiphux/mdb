<?php
/*
 *  title.titleinfo.php
 *  MDB: A media database
 *  Component: Title - titleinfo
 *  Fetches and returns all data on a given title
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

  include_once('display.message.php');

 function titleinfo($tid)
 {
 	global $tables, $cache;
	if (!isset($tid)) {
		message("No title specified","warning");
		return;
	}

	$tmp = $cache->get("tid" . $tid);
	if ($tmp)
		return $tmp;

	$title = DBGetRow("SELECT * FROM " . $tables['titles'] . " WHERE id=" . $tid);
	if (!$title) {
		message("No such title","warning");
		return;
	}
	$temp = DBGetArray("SELECT id,tid,size,SUBSTRING(file FROM " . (strlen($title['path'])+2) . ") AS file FROM " . $tables['files'] . " WHERE tid=" . $tid . " ORDER BY file");
	if (sizeof($temp) > 0)
		$title['files'] = $temp;
	$temp = DBGetArray("SELECT t1.* FROM " . $tables['tags'] . " AS t1, " . $tables['title_tag'] . " AS t2 WHERE t2.title_id=" . $tid . " AND t2.tag_id=t1.id ORDER BY t1.tag");
	if (sizeof($temp) > 0)
		$title['tags'] = $temp;
	$title['size'] = DBGetOne("SELECT SUM(size) FROM " . $tables['files'] . " WHERE tid=" . $title['id']);
	$temp = DBGetArray("SELECT * FROM " . $tables['links'] . " WHERE title_id=" . $tid);
	if ($temp && (sizeof($temp) > 0))
		$title['links'] = $temp;
	
	$cache->set("tid" . $tid, $title);

	return $title;
 }

?>
