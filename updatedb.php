<?php
/*
 *  updatedb.php
 *  MDB: A media database
 *  Component: Database updater
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Library General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 */

 if (shell_exec("ps ax | grep -v 'grep' | grep -c 'php updatedb.php'") > 1)
 	exit;

 include_once('db.inc.php');

 function fsize($file)
 {
 	$sz = @filesize($file);
	if (!$sz)
		return shell_exec("ls -l \"" . $file . "\" | cut -d ' ' -f 5");
	return $sz;
 }

 function insert_file($file)
 {
 	global $db,$tables,$mdb_conf;
	if (!in_array(substr($file,-3),$mdb_conf['ext_excludes']))
		$db->Execute("INSERT INTO " . $tables['files'] . " (file,size) VALUES (" . $db->qstr(substr($file,strlen($mdb_conf['root'])+1)) . "," . $db->qstr(fsize($file)) . ") ON DUPLICATE KEY UPDATE size=VALUES(size)");
 }

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

 function prune_titles_2($deleted)
 {
 	global $db,$tables;
	foreach ($deleted as $i => $k) {
		$db->Execute("DELETE FROM " . $tables['title_tag'] . " WHERE title_id=" . $k);
		$db->Execute("DELETE FROM " . $tables['animenfo'] . " WHERE title_id=" . $k);
	}
 }

 function maintain_associations()
 {
 	global $db,$tables,$mdb_conf;
	$files = $db->GetArray("SELECT * FROM " . $tables['files']);
	$titles = $db->GetArray("SELECT * FROM " . $tables['titles']);
	foreach ($files as $i => $file) {
		foreach ($titles as $j => $title) {
			if (strpos($file['file'],$title['path']) !== false) {
				$db->Execute("INSERT INTO " . $tables['file_title'] . " (file_id,title_id) VALUES (" . $file['id'] . "," . $title['id'] . ") ON DUPLICATE KEY UPDATE title_id=VALUES(title_id)");
			}
		}
	}
 }

 function optimizedb()
 {
 	global $db,$tables;
	foreach ($tables as $i => $table)
		$db->Execute("OPTIMIZE TABLE " . $db->qstr($table));
 }

 $del = prune_titles();

 update_titles($del);

 prunedb();

 index_dir($mdb_conf['root']);

 maintain_associations();

 optimizedb();

?> 
