<?php
/*
 *  updatedb.php
 *  MDB: A media database
 *  Component: Database updater
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */
 
 include_once('config/mdb.conf.php');
 include_once('db.php');
 include_once('database.updating.php');

 if (updating())
 	exit;

 $lastupdate = DBGetOne("SELECT UNIX_TIMESTAMP(MAX(time)) FROM " . $tables['dbupdate']);
 if ($lastupdate) {
 	$diff = time() - $lastupdate;
	if ($diff < $mdb_conf['dbupdate_wait'])
		exit;
 }

 /*
  * fsize
  * Gets filesize, falling back on shell execution of 'ls' if necessary
  */
 function fsize($file)
 {
 	$sz = @filesize($file);
	if (!$sz)
		return shell_exec("ls -l \"" . $file . "\" | cut -d ' ' -f 5");
	return $sz;
 }

 /*
  * insert_file
  * Inserts a file
  */
 function insert_file($file, $titlelist)
 {
 	global $tables,$mdb_conf;
	if (!in_array(substr($file,-3),$mdb_conf['ext_excludes'])) {
		foreach ($titlelist as $i => $title) {
			if (strpos($file,$title['path'] . "/") !== false) {
				$ok = DBExecute("INSERT INTO " . $tables['files'] . " (file,tid,size) VALUES (" . DBqstr(substr($file,strlen($mdb_conf['root'])+1)) . "," . $title['id'] . "," . DBqstr(fsize($file)) . ") ON DUPLICATE KEY UPDATE size=VALUES(size), tid=VALUES(tid)");
				if ($mdb_conf['debug'] && !$ok)
					echo "insert_file: " . DBErrorMsg() . "\n";
				return;
			}
		}
	}
 }

 /*
  * index_dir
  * Recursively indexes and inserts files in a dir
  */
 function index_dir($dir, $titlelist)
 {
 	global $mdb_conf;
 	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if (!(in_array($file,$mdb_conf['excludes']) || is_link($dir . "/" . $file) || (substr($file,0,1) == "."))) {
					if (is_dir($dir . "/" . $file))
						index_dir($dir . "/" . $file, $titlelist);
					else
						insert_file($dir . "/" . $file, $titlelist);
				}
			}
			closedir($dh);
		}
	}
 }
 
 /*
  * prunedb
  * Prunes out file entries that no longer exist
  */
 function prunedb()
 {
 	global $tables,$mdb_conf;
	$files = DBGetArray("SELECT * FROM " . $tables['files']);
	foreach ($files as $i => $file) {
		if ((!file_exists($mdb_conf['root'] . $file['file'])) || in_array(substr($file['file'],(strripos($file['file'],"/")===false?0:strripos($file['file'],"/")+1)),$mdb_conf['excludes']) || is_link($mdb_conf['root'] . $file['file']) || in_array(substr($file['file'],-3),$mdb_conf['ext_excludes'])) {
			$ok = DBExecute("DELETE FROM " . $tables['files'] . " WHERE id=" . $file['id']);
			if ($mdb_conf['debug'] && !$ok)
				echo "prunedb: " . DBErrorMsg() . "\n";
		}
	}
 }
 
 /*
  * Uppercases first letters of words, with certain exceptions
  */
 function uppercase($str)
 {
 	$lower = array(
		"to" => 1,
		"a" => 1,
		"the" => 1,
		"of" => 1,
	);
	$upper = array(
		"i" => 1,
		"ii" => 1,
		"iii" => 1,
		"iv" => 1,
		"v" => 1,
		"vi" => 1,
		"vii" => 1,
		"viii" => 1,
		"ix" => 1,
		"x" => 1,
	);
 	$a = explode(" ",$str);
	$size = count($a);
	for ($i = 0; $i < $size; $i++) {
		$t = strtolower($a[$i]);
		if ($upper[$t] === 1)
			$t = strtoupper($t);
		else if (($lower[$t] !== 1) || ($i === 0))
			$t = ucfirst($t);
		$a[$i] = $t;
	}
	return implode(" ",$a);
 }

 /*
  * update_titles
  * Inserts new titles, using stored ids from the
  * 'deleted' array if necessary
  */
 function update_titles(&$deleted)
 {
 	global $mdb_conf,$tables;
	foreach ($mdb_conf['titlebase'] as $i => $title) {
		if (is_dir($mdb_conf['root'] . $title)) {
			if ($dh = opendir($mdb_conf['root'] . $title)) {
				while (($file = readdir($dh)) !== false) {
					if (is_dir($mdb_conf['root'] . $title . "/" . $file) && !(in_array($file,$mdb_conf['excludes']) || (substr($file,0,1) == "."))) {
						$q = "INSERT IGNORE INTO " . $tables['titles'] . "(";
						$q2 = "path,title) VALUES(";
						$q3 = DBqstr($title . "/" . $file) . "," . DBqstr(uppercase($file)) . ")";
						$path = $title . "/" . $file;
						$bn = basename($path);
						if (isset($deleted[$bn])) {
							$q .= "id,";
							$q2 .= $deleted[$bn] . ",";
						}
						$ok = DBExecute($q . $q2 . $q3);
						if ($mdb_conf['debug'] && !$ok)
							echo "update_titles: " . DBErrorMsg() . "\n";
						unset($deleted[$bn]);
					}
				}
				closedir($dh);
			}
		}
	}
 }

 /*
  * prune_titles
  * Prune titles, step 1 - delete titles that no longer exist, and stores
  * deleted ids in the 'deleted' array
  */
 function prune_titles()
 {
 	global $tables,$mdb_conf;
	$deleted = array();
	$titles = DBGetArray("SELECT * FROM " . $tables['titles']);
	foreach ($titles as $i => $title) {
		if ((!file_exists($mdb_conf['root'] . $title['path'])) || is_link($mdb_conf['root'] . $title['path'])) {
			$ok = DBExecute("DELETE FROM " . $tables['titles'] . " WHERE id=" . $title['id']);
			if ($mdb_conf['debug'] && !$ok)
				echo "prune_titles: " . DBErrorMsg() . "\n";
			$deleted[basename($title['path'])] = $title['id'];
		}
	}
	return $deleted;
 }

 /*
  * prune_titles_2
  * Prune titles, step 2 - deletes tag/nfo metadata for
  * titles that are in the deleted array (and therefore
  * were not just migrated but fully deleted)
  */
 function prune_titles_2($deleted)
 {
 	global $tables,$mdb_conf;
	foreach ($deleted as $i => $k) {
		$ok = DBExecute("DELETE FROM " . $tables['title_tag'] . " WHERE title_id=" . $k);
		if ($mdb_conf['debug'] && !$ok)
			echo "prune_titles_2:1: " . DBErrorMsg() . "\n";
		$ok = DBExecute("DELETE FROM " . $tables['links'] . " WHERE title_id=" . $k);
		if ($mdb_conf['debug'] && !$ok)
			echo "prune_titles_2:2: " . DBErrorMsg() . "\n";
	}
 }

 /*
  * optimizedb
  * Calls mysql optimize on all tables
  */
 function optimizedb()
 {
 	global $tables,$mdb_conf;
	$ok = DBExecute("OPTIMIZE TABLE " . implode(",",$tables));
	if ($mdb_conf['debug'] && !$ok)
		echo "optimizedb: " . DBErrorMsg() . "\n";
 }

 if ($mdb_conf['dbmutex']) {
 	$ok = DBExecute("INSERT INTO " . $tables['dbupdate'] . " (progress) VALUES(1)");
	if ($mdb_conf['debug'] && !$ok)
		echo "dbmutex init: " . DBErrorMsg() . "\n";
 }

 DBStartTrans();

 if ($mdb_conf['debug'])
 	echo "prune_titles()\n";
	
 $del = prune_titles();

 if ($mdb_conf['debug'])
 	echo "update_titles()\n";

 update_titles($del);

 if ($mdb_conf['debug'])
 	echo "prune_titles_2()\n";

 prune_titles_2($del);

 if ($mdb_conf['debug'])
 	echo "prunedb()\n";

 prunedb();

 if ($mdb_conf['debug'])
 	echo "index_dir()\n";

 $newtitlelist = DBGetArray("SELECT * FROM " . $tables['titles']);
 index_dir($mdb_conf['root'], $newtitlelist);

 if ($mdb_conf['optimize']) {
 	if ($mdb_conf['debug'])
		echo "optimizedb()\n";
 	optimizedb();
 }

 if (!($mdb_conf['dbmutex'])) {
 	$ok = DBExecute("INSERT INTO " . $tables['dbupdate'] . " (progress) VALUES(0)");
	if ($mdb_conf['debug'] && !$ok)
		echo "non-dbmutex: " . DBErrorMsg() . "\n";
 }

 $success = DBCompleteTrans();

 if ($success) {
 	mdb_memcache_flush();
 }

 if ($mdb_conf['dbmutex']) {
 	if ($success) {
 		$ok = DBExecute("UPDATE " . $tables['dbupdate'] . " SET progress=0 WHERE progress!=0");
		if ($mdb_conf['debug'] && !$ok)
			echo "dbmutex success: " . DBErrorMsg() . "\n";
	} else {
		$ok = DBExecute("DELETE FROM " . $tables['dbupdate'] . " WHERE progress!=0");
		if ($mdb_conf['debug'] && !$ok)
			echo "dbmutex failure: " . DBErrorMsg() . "\n";
	}
 }

 if ($mdb_conf['debug']) {
 	echo "DBUpdate ";
	if ($success)
		echo "succeeded";
	else
		echo "failed";
	echo " (" . $querycount . " queries)\n";
 }

?> 
