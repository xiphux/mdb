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
 	global $tables, $cache;
	$results = array();
	if (!(isset($search) && (strlen($search) > 0))) {
		message("Invalid search string","warning");
		return;
	}
	if ($criteria === "All" || $criteria === "Titles") {
		$tmp = $cache->get("search_titles_" . md5($search));
		if ($tmp)
			$results['titles'] = $tmp;
		else {
			$ret = DBGetArray("SELECT * FROM " . $tables['titles'] . " WHERE title LIKE '%" . addslashes($search) . "%' ORDER BY title");
			$size = count($ret);
			if ($size > 0) {
				for ($i = 0; $i < $size; $i++)
					highlight($ret[$i]['title'],$search);
				$results['titles'] = $ret;
				$cache->set("search_titles_" . md5($search), $ret);
			}
		}
	}
	if ($criteria === "All" || $criteria === "Files") {
		$tmp = $cache->get("search_files_" . md5($search));
		if ($tmp)
			$results['files'] = $tmp;
		else {
			$ret = DBGetArray("SELECT * FROM " . $tables['files'] . " WHERE file LIKE '%/%/%" . addslashes($search) . "%' ORDER BY file");
			$size = count($ret);
			if ($size > 0) {
				for ($i = 0; $i < $size; $i++)
					highlight($ret[$i]['file'],$search);
				$results['files'] = $ret;
				$cache->set("search_files_" . md5($search), $ret);
			}
		}
	}
	return $results;
 }

?>
