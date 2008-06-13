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
 	global $db,$tables;
	if (!isset($tid)) {
		message("No title specified","warning");
		return;
	}
	$title = $db->GetRow("SELECT * FROM " . $tables['titles'] . " WHERE id=" . $tid . " LIMIT 1");
	if (!$title) {
		message("No such title","warning");
		return;
	}
	$temp = $db->GetArray("SELECT t1.* FROM " . $tables['files'] . " AS t1, " . $tables['file_title'] . " AS t2 WHERE t2.title_id=" . $tid . " AND t2.file_id=t1.id ORDER BY t1.file");
	if (sizeof($temp) > 0) {
		$len = strlen($title['path'])+1;
		$size = count($temp);
		for ($i = 0; $i < $size; $i++)
			$temp[$i]['file'] = substr($temp[$i]['file'],$len);
		$title['files'] = $temp;
	}
	$temp = $db->GetArray("SELECT t1.* FROM " . $tables['tags'] . " AS t1, " . $tables['title_tag'] . " AS t2 WHERE t2.title_id=" . $tid . " AND t2.tag_id=t1.id ORDER BY t1.tag");
	if (sizeof($temp) > 0)
		$title['tags'] = $temp;
	$title['size'] = $db->GetOne("SELECT SUM(t1.size) FROM " . $tables['files'] . " AS t1, " . $tables['file_title'] . " AS t2 WHERE t1.id=t2.file_id AND t2.title_id=" . $title['id']);
	$temp = $db->GetArray("SELECT * FROM " . $tables['links'] . " WHERE title_id=" . $tid);
	if ($temp && (sizeof($temp) > 0))
		$title['links'] = $temp;
	return $title;
 }

?>
