<?php
/*
 *  util.search.php
 *  MDB: A media database
 *  Component: Utility - search
 *  Searches the database for a string according to certain
 *  criteria
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

  include_once('util.highlight.php');
  include_once('display.message.php');

 function search($search,$criteria)
 {
 	global $db,$tables;
	if (!(isset($search) && (strlen($search) > 0))) {
		message("Invalid search string","warning");
		return;
	}
	if ($criteria === "All" || $criteria === "Titles") {
		$ret = $db->GetArray("SELECT * FROM " . $tables['titles'] . " WHERE title LIKE '%" . addslashes($search) . "%' ORDER BY title");
		$size = count($ret);
		if ($size > 0) {
			for ($i = 0; $i < $size; $i++)
				highlight($ret[$i]['title'],$search);
			$results['titles'] = $ret;
		}
	}
	if ($criteria === "All" || $criteria === "Files") {
		$ret = $db->GetArray("SELECT * FROM " . $tables['files'] . " WHERE file LIKE '%/%/%" . addslashes($search) . "%' ORDER BY file");
		$size = count($ret);
		if ($size > 0) {
			for ($i = 0; $i < $size; $i++)
				highlight($ret[$i]['file'],$search);
			$results['files'] = $ret;
		}
	}
	return $results;
 }

?>
