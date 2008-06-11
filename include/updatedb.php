<?php
/*
 *  updatedb.php
 *  MDB: A media database
 *  Component: Database updater
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */
 
 include_once('config/mdb.conf.php');

 if ((!($mdb_conf['dbmutex'])) && (shell_exec("ps ax | grep -v 'grep' | grep -c '" . $mdb_conf['phpexec'] . " include/updatedb.php'") >= 1))
 	exit;

 include_once('db.php');

 if ($mdb_conf['dbmutex']) {
 	$status = $db->GetOne("SELECT MAX(progress) FROM " . $tables['dbupdate']);
	if ($status && $status > 0)
		exit;
 }

 $lastupdate = $db->GetOne("SELECT UNIX_TIMESTAMP(MAX(time)) FROM " . $tables['dbupdate']);
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
 function insert_file($file)
 {
 	global $db,$tables,$mdb_conf;
	if (!in_array(substr($file,-3),$mdb_conf['ext_excludes']))
		$db->Execute("INSERT INTO " . $tables['files'] . " (file,size) VALUES (" . $db->qstr(substr($file,strlen($mdb_conf['root'])+1)) . "," . $db->qstr(fsize($file)) . ") ON DUPLICATE KEY UPDATE size=VALUES(size)");
 }

 /*
  * index_dir
  * Recursively indexes and inserts files in a dir
  */
 function index_dir($dir)
 {
 	global $mdb_conf;
 	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if (!(in_array($file,$mdb_conf['excludes']) || is_link($dir . "/" . $file) || (substr($file,0,1) == "."))) {
					if (is_dir($dir . "/" . $file))
						index_dir($dir . "/" . $file);
					else
						insert_file($dir . "/" . $file);
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
 	global $db,$tables,$mdb_conf;
	$files = $db->GetArray("SELECT * FROM " . $tables['files']);
	foreach ($files as $i => $file) {
		if ((!file_exists($mdb_conf['root'] . $file['file'])) || in_array(substr($file['file'],(strripos($file['file'],"/")===false?0:strripos($file['file'],"/")+1)),$mdb_conf['excludes']) || is_link($mdb_conf['root'] . $file['file']) || in_array(substr($file['file'],-3),$mdb_conf['ext_excludes'])) {
			$db->Execute("DELETE FROM " . $tables['files'] . " WHERE id=" . $file['id'] . " LIMIT 1");
			$db->Execute("DELETE FROM " . $tables['file_title'] . " WHERE file_id=" . $file['id']);
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
 	global $mdb_conf,$db,$tables;
	foreach ($mdb_conf['titlebase'] as $i => $title) {
		if (is_dir($mdb_conf['root'] . $title)) {
			if ($dh = opendir($mdb_conf['root'] . $title)) {
				while (($file = readdir($dh)) !== false) {
					if (!(in_array($file,$mdb_conf['excludes']) || is_link($mdb_conf['root'] . $title . "/" . $file) || (substr($file,0,1) == "."))) {
						$q = "INSERT IGNORE INTO " . $tables['titles'] . "(";
						$q2 = "path,title) VALUES(";
						$q3 = $db->qstr($title . "/" . $file) . "," . $db->qstr(uppercase($file)) . ")";
						$path = $title . "/" . $file;
						$bn = basename($path);
						if (isset($deleted[$bn])) {
							$q .= "id,";
							$q2 .= $deleted[$bn] . ",";
						}
						$db->Execute($q . $q2 . $q3);
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
 	global $db,$tables,$mdb_conf;
	$deleted = array();
	$titles = $db->GetArray("SELECT * FROM " . $tables['titles']);
	foreach ($titles as $i => $title) {
		if ((!file_exists($mdb_conf['root'] . $title['path'])) || is_link($mdb_conf['root'] . $title['path'])) {
			$db->Execute("DELETE FROM " . $tables['titles'] . " WHERE id=" . $title['id'] . " LIMIT 1");
			$db->Execute("DELETE FROM " . $tables['file_title'] . " WHERE title_id=" . $title['id']);
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
 	global $db,$tables;
	foreach ($deleted as $i => $k) {
		$db->Execute("DELETE FROM " . $tables['title_tag'] . " WHERE title_id=" . $k);
		$db->Execute("DELETE FROM " . $tables['animenfo'] . " WHERE title_id=" . $k);
	}
 }

 /*
  * maintain_associations
  * Updates file/title associations
  */
 function maintain_associations()
 {
 	global $db,$tables,$mdb_conf;
	$files = $db->GetArray("SELECT * FROM " . $tables['files']);
	$titles = $db->GetArray("SELECT * FROM " . $tables['titles']);
	foreach ($files as $i => $file) {
		foreach ($titles as $j => $title) {
			if (strpos($file['file'],$title['path'] . "/") !== false) {
				$db->Execute("INSERT INTO " . $tables['file_title'] . " (file_id,title_id) VALUES (" . $file['id'] . "," . $title['id'] . ") ON DUPLICATE KEY UPDATE title_id=VALUES(title_id)");
			}
		}
	}
 }

 /*
  * optimizedb
  * Calls mysql optimize on all tables
  */
 function optimizedb()
 {
 	global $db,$tables;
	foreach ($tables as $i => $table)
		$db->Execute("OPTIMIZE TABLE " . $db->qstr($table));
 }

 if ($mdb_conf['dbmutex'])
 	$db->Execute("INSERT INTO " . $tables['dbupdate'] . " (progress) VALUES(1)");

 $db->StartTrans();
 
 $del = prune_titles();

 update_titles($del);

 prune_titles_2($del);

 prunedb();

 index_dir($mdb_conf['root']);

 maintain_associations();

 optimizedb();

 if (!($mdb_conf['dbmutex']))
 	$db->Execute("INSERT INTO " . $tables['dbupdate'] . " (progress) VALUES(0)");

 $success = $db->CompleteTrans();

 if ($mdb_conf['dbmutex']) {
 	if ($success)
 		$db->Execute("UPDATE " . $tables['dbupdate'] . " SET progress=0 WHERE progress!=0");
	else
		$db->Execute("DELETE FROM " . $tables['dbupdate'] . " WHERE progress!=0");
 }

?> 
