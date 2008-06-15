<?php
/*
 *  title.titlelist.php
 *  MDB: A media database
 *  Component: Title - titlelist
 *  Returns a list of titles sorted by first letter/number
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

 define('QUERY_TO_ITERATION_RATIO',50);
 define('SQL_QUERIES',37);

 function titlelist()
 {
 	global $tables;
	$titles = array();
	$titlecount = DBGetOne("SELECT COUNT(id) FROM " . $tables['titles']);
	if ($titlecount > (SQL_QUERIES * QUERY_TO_ITERATION_RATIO)) {
		$letters = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
		$numbers = array("0","1","2","3","4","5","6","7","8","9");

		$stmt = DBPrepare("SELECT * FROM " . $tables['titles'] . " WHERE LEFT(TRIM(title),1)=? ORDER BY title");
		foreach ($numbers as $i => $number) {
			$temp = DBGetArray($stmt,array($number));
			if (sizeof($temp) > 0)
				$titles[$number] = $temp;
		}

		$stmt = DBPrepare("SELECT * FROM " . $tables['titles'] . " WHERE LEFT(UPPER(TRIM(title)),1)=? ORDER BY title");
		foreach ($letters as $i => $letter) {
			$temp = DBGetArray($stmt,array($letter));
			if (sizeof($temp) > 0)
				$titles[strtolower($letter)] = $temp;
		}

		$temp = DBGetArray("SELECT * FROM " . $tables['titles'] . " WHERE LEFT(UPPER(TRIM(title)),1) NOT IN ('" . implode("','",$letters) . "','" . implode("','",$numbers) . "') ORDER BY title");
		if (sizeof($temp) > 0)
			$titles['other'] = $temp;
	} else {
		$titlelist = DBGetArray("SELECT id, title FROM " . $tables['titles'] . " ORDER BY title");
		foreach ($titlelist as $i => $title) {
			$titles[substr(strtolower(trim($titlelist[$i]["title"])),0,1)][] = $titlelist[$i];
		}
	}
	return $titles;
 }

?>
