<?php
/*
 *  mdb.lib.php
 *  MDB: A media database
 *  Component: Function library
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

 function titlelist()
 {
 	global $db,$tables;
	$letters = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
	$numbers = array("0","1","2","3","4","5","6","7","8","9");
	foreach ($numbers as $i => $number) {
		$temp = $db->GetArray("SELECT * FROM " . $tables['titles'] . " WHERE LEFT(TRIM(title),1)=" . $db->qstr($number) . " ORDER BY title");
		if (sizeof($temp) > 0)
			$titles[$number] = $temp;
	}
	foreach ($letters as $i => $letter) {
		$temp = $db->GetArray("SELECT * FROM " . $tables['titles'] . " WHERE LEFT(UPPER(TRIM(title)),1)=" . $db->qstr($letter) . " ORDER BY title");
		if (sizeof($temp) > 0)
			$titles[strtolower($letter)] = $temp;
	}
	$temp = $db->GetArray("SELECT * FROM " . $tables['titles'] . " WHERE LEFT(UPPER(TRIM(title)),1) NOT IN ('" . implode("','",$letters) . "','" . implode("','",$numbers) . "') ORDER BY title");
	if (sizeof($temp) > 0)
		$titles['other'] = $temp;
	return $titles;
 }

 function login($user,$pass)
 {
 	global $mdb_conf,$db,$tables;
	if (!(isset($user) && strlen($user) > 0)) {
		echo "No username entered";
		return;
	}
	if (!(isset($pass) && strlen($pass) > 0)) {
		echo "No password entered";
		return;
	}
	$u = $db->GetRow("SELECT * FROM " . $tables['users'] . " WHERE username=" . $db->qstr($user) . " LIMIT 1");
	if (!$u) {
		echo "No such user";
		return;
	}
	if (md5($pass) !== $u['password']) {
		echo "Incorrect password";
		return;
	}
	$_SESSION[$mdb_conf['session_key']]['user'] = $u;
	echo "Logged in successfully";
 }

 function updatedb()
 {
 	global $mdb_conf;
	if (!(isset($_SESSION[$mdb_conf['session_key']]['user']) && ($_SESSION[$mdb_conf['session_key']]['user']['privilege'] > 0))) {
		echo "You do not have access to this feature!";
		return;
	}
	exec("php updatedb.php 2>/dev/null >&- <&- >/dev/null &");
	echo "Database updating";
 }

function highlight(&$string, $substr, $type = "highlight")
{
	$string = "<span>" . $string . "</span>";
	$string = eregi_replace("(>[^<]*)(" . quotemeta($substr) . ")","\\1<span class=\"" . $type . "\">\\2</span>",$string);
	$string = eregi_replace("<span>","",$string);
	$string = substr($string,0,-7);
}

 function search($search,$criteria)
 {
 	global $db,$tables;
	if (!(isset($search) && (strlen($search) > 0))) {
		echo "Invalid search string";
		return;
	}
	if ($criteria === "All" || $criteria === "Titles") {
		$ret = $db->GetArray("SELECT * FROM " . $tables['titles'] . " WHERE title LIKE '%" . $search . "%' ORDER BY title");
		$size = count($ret);
		if ($size > 0) {
			for ($i = 0; $i < $size; $i++)
				highlight($ret[$i]['title'],$search);
			$results['titles'] = $ret;
		}
	}
	if ($criteria === "All" || $criteria === "Files") {
		$ret = $db->GetArray("SELECT * FROM " . $tables['files'] . " WHERE file LIKE '%/%/%" . $search . "%' ORDER BY file");
		$size = count($ret);
		if ($size > 0) {
			for ($i = 0; $i < $size; $i++)
				highlight($ret[$i]['file'],$search);
			$results['files'] = $ret;
		}
	}
	return $results;
 }

 function titleinfo($tid)
 {
 	global $db,$tables;
	if (!isset($tid)) {
		echo "No title specified";
		return;
	}
	$title = $db->GetRow("SELECT * FROM " . $tables['titles'] . " WHERE id=" . $tid . " LIMIT 1");
	if (!$title) {
		echo "No such title";
		return;
	}
	$temp = $db->GetArray("SELECT t1.id,t1.file FROM " . $tables['files'] . " AS t1, " . $tables['file_title'] . " AS t2 WHERE t2.title_id=" . $tid . " AND t2.file_id=t1.id ORDER BY t1.file");
	if (sizeof($temp) > 0) {
		$len = strlen($title['path'])+1;
		$size = count($temp);
		for ($i = 0; $i < $size; $i++)
			$temp[$i]['file'] = substr($temp[$i]['file'],$len);
		$title['files'] = $temp;
	}
	return $title;
 }

?>
