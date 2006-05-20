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

 function insert_file($file)
 {
 	global $db,$tables,$mdb_conf;
	$db->Execute("INSERT IGNORE INTO " . $tables['files'] . " (file) VALUES (" . $db->qstr(substr($file,strlen($mdb_conf['root'])+1)) . ")");
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
		if ((!file_exists($mdb_conf['root'] . $file['file'])) || in_array(substr($file['file'],(strripos($file['file'],"/")===false?0:strripos($file['file'],"/")+1)),$mdb_conf['excludes']) || is_link($mdb_conf['root'] . $file['file'])) {
			//echo $mdb_conf['root'] . $file['file'] . "\n";
			$db->Execute("DELETE FROM " . $tables['files'] . " WHERE id=" . $file['id'] . " LIMIT 1");
			$db->Execute("DELETE FROM " . $tables['file_title'] . " WHERE file_id=" . $file['id']);
			//echo "DELETE FROM " . $tables['files'] . " WHERE id=" . $file['id'] . " LIMIT 1" . "\n";
			//echo "DELETE FROM " . $tables['file_title'] . " WHERE file_id=" . $file['id'] . "\n";
		}
	}
 }

 function update_titles()
 {
 	global $mdb_conf,$db,$tables;
	foreach ($mdb_conf['titlebase'] as $i => $title) {
		if (is_dir($mdb_conf['root'] . $title)) {
			if ($dh = opendir($mdb_conf['root'] . $title)) {
				while (($file = readdir($dh)) !== false) {
					if (!(in_array($file,$mdb_conf['excludes']) || is_link($mdb_conf['root'] . $title . "/" . $file) || (substr($file,0,1) == "."))) {
						$db->Execute("INSERT IGNORE INTO " . $tables['titles'] . " (path,title) VALUES (" . $db->qstr($title . "/" . $file) . "," . $db->qstr(ucwords(strtolower($file))) . ")");
						//echo "INSERT IGNORE INTO " . $tables['titles'] . " (path,title) VALUES (" . $db->qstr($title . "/" . $file) . "," . $db->qstr(ucwords(strtolower($file))) . ")" . "\n";
						
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
	$titles = $db->GetArray("SELECT * FROM " . $tables['titles']);
	foreach ($titles as $i => $title) {
		if ((!file_exists($mdb_conf['root'] . $title['path'])) || is_link($mdb_conf['root'] . $file['file'])) {
			$db->Execute("DELETE FROM " . $tables['titles'] . " WHERE id=" . $title['id'] . " LIMIT 1");
			$db->Execute("DELETE FROM " . $tables['file_title'] . " WHERE title_id=" . $title['id']);
			//echo "DELETE FROM " . $tables['titles'] . " WHERE id=" . $title['id'] . " LIMIT 1" . "\n";
			//echo "DELETE FROM " . $tables['file_title'] . " WHERE title_id=" . $title['id'] . "\n";
		}
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
				//echo "INSERT INTO " . $tables['file_title'] . " (file_id,title_id) VALUES (" . $file['id'] . "," . $title['id'] . ") ON DUPLICATE KEY UPDATE title_id=VALUES(title_id)" . "\n";
				//echo $file['file'] . " => " . $title['title'] . "\n";
			}
		}
	}
 }

 prune_titles();

 update_titles();

 prunedb();

 index_dir($mdb_conf['root']);

 maintain_associations();

?> 
