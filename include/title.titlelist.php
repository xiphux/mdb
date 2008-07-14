<?php
/*
 *  title.titlelist.php
 *  MDB: A media database
 *  Component: Title - titlelist
 *  Returns a list of titles sorted by first letter/number
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

 /*
  * These constants define which operation to perform to
  * enumerate the titlelist.
  * One solution is to have one select for every letter
  * in the alphabet and every number, and one for
  * non-alphanumerics, yielding 37 selects.
  * The other solution is to select all and iterate
  * through everything.
  * The speed of the iteration will depend on how many
  * titles are in the list; iterating through 100 titles
  * is very quick, but iterating through 100,000 titles
  * is slow.  On the other hand, using multiple selects
  * is always fixed at 37 selects and has an execution
  * time independent of the number of titles.
  * Therefore the system will get the title count, and
  * choose the proper solution.  If the title count is
  * below the QUERY_TO_ITERATION_RATIO * SQL_QUERIES,
  * it will iterate, otherwise it will select.
  * From my testing and calculations, on my machine with
  * an optimal setup (mysql on the same server as php,
  * php running eaccelerator, adodb-ext), it was estimated
  * that the iteration would be slower than the selects
  * when the title count surpassed about 1850, therefore 
  * the ratio is set at 50 (37 * 50 = 1850).  If your setup
  * is not as optimal (mysql on a slower separate database
  * server, php not running eaccelerator, no adodb-ext, etc),
  * you may want to adjust this ratio.
  */
 define('QUERY_TO_ITERATION_RATIO',50);
 define('SQL_QUERIES',37);

 function titlelist()
 {
 	global $tables, $cache;

	$tmp = $cache->get("titlelist");
	if ($tmp)
		return $tmp;
	
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

	$cache->set("titlelist",$titles);

	return $titles;
 }

?>
