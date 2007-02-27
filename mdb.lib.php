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

 /*
  * Base animenfo url
  */
 define('ANIMENFO_BASE','http://www.animenfo.com');

 /*
  * animenfo_link
  * Returns full animenfo link from array of link data
  */
 function animenfo_link($data)
 {
 	$url = ANIMENFO_BASE;
	if (!(isset($data['nfo_id']) && isset($data['nfo_type']) && isset($data['nfo_n']) && isset($data['nfo_t'])))
		return $url;
	$url .= "/" . $data['nfo_type'] . "title" . "," . $data['nfo_id'] . "," . $data['nfo_n'] . "," . $data['nfo_t'] . ".html";
	return $url;
 }

 /*
  * titlelist
  * Returns a list of titles sorted by first letter/number
  */
 function titlelist()
 {
 	global $db,$tables,$mdb_conf;
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

 /*
  * login
  * Tests a given user/pass for validity and sets the session key
  * if legitimate
  */
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

 /*
  * updatedb
  * Executes the updatedb script in the background
  * and displays the update screen
  */
 function updatedb()
 {
 	global $mdb_conf,$tpl;
	if (!(isset($_SESSION[$mdb_conf['session_key']]['user']) && ($_SESSION[$mdb_conf['session_key']]['user']['privilege'] > 0))) {
		echo "You do not have access to this feature!";
		return;
	}
	exec("php updatedb.php 2>/dev/null >&- <&- >/dev/null &");
	$tpl->clear_all_assign();
	$tpl->assign("interval",$mdb_conf['updatedbstatus_interval']);
	$tpl->display("updatedb.tpl");
 }

 /*
  * highlight
  * Highlights a substring in a given string
  */
 function highlight(&$string, $substr, $type = "highlight")
 {
	$string = "<span>" . $string . "</span>";
	$string = eregi_replace("(>[^<]*)(" . quotemeta($substr) . ")","\\1<span class=\"" . $type . "\">\\2</span>",$string);
	$string = eregi_replace("<span>","",$string);
	$string = substr($string,0,-7);
 }

 /*
  * search
  * Searches the database for a string according to certain
  * criteria
  */
 function search($search,$criteria)
 {
 	global $db,$tables,$mdb_conf;
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

 /*
  * titleinfo
  * Fetches and returns all data on a given title
  */
 function titleinfo($tid)
 {
 	global $db,$tables,$mdb_conf;
	if (!isset($tid)) {
		echo "No title specified";
		return;
	}
	$title = $db->GetRow("SELECT * FROM " . $tables['titles'] . " WHERE id=" . $tid . " LIMIT 1");
	if (!$title) {
		echo "No such title";
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
	$temp = $db->GetArray("SELECT * FROM " . $tables['animenfo'] . " WHERE title_id=" . $tid);
	if ($temp && (sizeof($temp) > 0)) {
		$size = sizeof($temp);
		for ($i = 0; $i < $size; $i++) {
			$temp[$i]['link'] = animenfo_link($temp[$i]);
		}
		$title['info']['animenfo'] = $temp;
	}
	return $title;
 }

 /*
  * readfile_chunked
  * Reads a file and sends it to the browser in chunks
  */
 function readfile_chunked($filename) {
 	$chunksize = 1 * (1024 * 1024);
	$buffer = '';
	$handle = fopen($filename,'rb');
	if ($handle === false)
		return false;
	while (!feof($handle)) {
		$buffer = fread($handle,$chunksize);
		echo $buffer;
		ob_flush();
		flush();
	}
	return fclose($handle);
}

/*
 * dbstats
 * Displays database statistics
 */
function dbstats()
{
	global $mdb_conf,$db,$tpl,$tables,$mdb_appstring;
	$tpl->clear_all_assign();
	$tpl->assign("appstring",$mdb_appstring);
	$tpl->assign("cdate","2006");
	$tpl->assign("cauthor_email","xiphux@gmail.com");
	$tpl->assign("cauthor","Christopher Han");
	$uptime = @exec('uptime');
	preg_match("/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/",$uptime,$avgs);
	$uptime = explode(' up ', $uptime);
	$uptime = explode(',', $uptime[1]);
	$uptime = $uptime[0].', '.$uptime[1];
	$start=mktime(0, 0, 0, 1, 1, date("Y"), 0);
	$end=mktime(0, 0, 0, date("m"), date("j"), date("y"), 0);
	$diff=$end-$start;
	$days=$diff/86400;
	$percentage=($uptime/$days) * 100;
	$load=$avgs[1].",".$avgs[2].",".$avgs[3]."";
	$tpl->assign("server",getenv('SERVER_NAME'));
	$tpl->assign("uname",htmlentities(@exec('uname -a'),ENT_COMPAT,'UTF-8'));
	$tpl->assign("uptime_days",$uptime);
	$tpl->assign("uptime_percent",$percentage);
	$tpl->assign("loadavg",$load);
	$tpl->assign("files",$db->GetOne("SELECT COUNT(id) FROM " . $tables['files']));
	$tpl->assign("titles",$db->GetOne("SELECT COUNT(id) FROM " . $tables['titles']));
	$tpl->assign("users",$db->GetOne("SELECT COUNT(id) FROM " . $tables['users']));
	$tpl->display("stats.tpl");
	$dbstats = $db->GetArray("SHOW TABLE STATUS");
	$total = 0;
	foreach ($dbstats as $row) {
		if (in_array($row['Name'],$tables)) {
			if ($mdb_conf['optimize'])
				$db->Execute("OPTIMIZE TABLE " . $db->qstr($row['Name']));
			$tpl->clear_all_assign();
			$tpl->assign("table",$row);
			if (isset($row['Data_length']) && isset($row['Index_length'])) {
				$t = $row['Data_length'] + $row['Index_length'];
				$tpl->assign("total_size",$t);
				$total += $t;
				$tpl->display("stats_table.tpl");
			}
		}
	}
	$tpl->clear_all_assign();
	$tpl->assign("dbsize",$total);
	$tpl->display("stats_sum.tpl");
}

/*
 * unmapped
 * Tests for any files that are not mapped to a title
 */
function unmapped()
{
	global $db,$tables;
	$temp = null;
	$unmap = array();
	$files = $db->GetArray("SELECT * FROM " . $tables['files']);
	foreach ($files as $i => $file) {
		$temp = $db->GetArray("SELECT * FROM " . $tables['file_title'] . " WHERE id=" . $file['id'] . " LIMIT 1");
		if (sizeof($temp) < 1)
			$unmap[] = $file;
	}
	if (sizeof($unmap) > 0)
		return $unmap;
	return null;
}

/**
 * Return human readable sizes
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.1.0
 * @link        http://aidanlister.com/repos/v/function.size_readable.php
 * @param       int    $size        Size
 * @param       int    $unit        The maximum unit
 * @param       int    $retstring   The return string format
 * @param       int    $si          Whether to use SI prefixes
 *
 * (Slightly modified by Christopher Han)
 */
function size_readable($size, $unit = null, $retstring = null, $si = true)
{
    // Units
    if ($si === true) {
        $sizes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB', 'BB');
        $mod   = 1000;
    } else {
        $sizes = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB');
        $mod   = 1024;
    }
    $ii = count($sizes) - 1;
 
    // Max unit
    $unit = array_search((string) $unit, $sizes);
    if ($unit === null || $unit === false)
        $unit = $ii;
 
    // Return string
    if ($retstring === null)
       $retstring = '%01.2f %s';
 
    // Loop
    for ($i = 0; $unit != $i && $size >= 1024 && $i < $ii; $i++)
        $size /= $mod;
 
    return sprintf($retstring, $size, $sizes[$i]);
}

/*
 * mainpage
 * Displays main menu page
 */
function mainpage()
{
	global $tpl,$mdb_appstring,$db,$tables,$mdb_conf;
	$tpl->clear_all_assign();
	$tpl->assign("banner",$mdb_appstring);
	$tpl->assign("titlecount",$db->GetOne("SELECT COUNT(id) FROM " . $tables['titles']));
	$tpl->assign("filecount",$db->GetOne("SELECT COUNT(id) FROM " . $tables['files']));
	$tpl->assign("size",$db->GetOne("SELECT SUM(size) FROM " . $tables['files']));
	$tpl->display("main.tpl");
}

/*
 * taginfo
 * Fetches all info on a given tag
 */
function taginfo($id)
{
	global $db,$tables,$mdb_conf;
	if (!isset($id))
		return null;
	$tag = $db->GetRow("SELECT * FROM " . $tables['tags'] . " WHERE id=" . $id . " LIMIT 1");
	if (!$tag)
		return null;
	$temp = $db->GetArray("SELECT t1.* FROM " . $tables['titles'] . " AS t1, " . $tables['title_tag'] . " AS t2 WHERE t2.tag_id=" . $id . " AND t2.title_id=t1.id ORDER BY t1.title");
	if (sizeof($temp) > 0)
		$tag['titles'] = $temp;
	$tag['count'] = sizeof($temp);
	return $tag;
}

/*
 * addtag
 * Adds a tag to a title (creating new tag if necessary)
 */
function addtag($tid,$tag)
{
	global $db,$tables,$mdb_conf;
	if (!isset($_SESSION[$mdb_conf['session_key']]['user'])) {
		echo "You do not have access to this feature!";
		return;
	}
	if (!$tid) {
		echo "No series specified";
		return;
	}
	$tag = trim($tag);
	if (!(isset($tag) && (strlen($tag) > 0))) {
		echo "No tag specified";
		return;
	}
	$id = $db->GetOne("SELECT id FROM " . $tables['tags'] . " WHERE UPPER(tag) LIKE '%" . strtoupper($tag) . "%' LIMIT 1");
	if (!$id) {
		$db->Execute("INSERT INTO " . $tables['tags'] . " (tag) VALUES (" . $db->qstr(strtolower($tag)) . ")");
		$id = $db->_insertid();
	}
	$db->Execute("INSERT IGNORE INTO " . $tables['title_tag'] . " (title_id,tag_id) VALUES (" . $tid . "," . $id . ")");
	return;
}

/*
 * taglist
 * Returns list of tags with counts
 */
function taglist()
{
	global $db,$tables,$mdb_conf;
	$tags = $db->GetArray("SELECT * FROM " . $tables['tags'] . " ORDER BY tag");
	$size = count($tags);
	for ($i = 0; $i < $size; $i++)
		$tags[$i]['count'] = $db->GetOne("SELECT COUNT(title_id) FROM " . $tables['title_tag'] . " WHERE tag_id=" . $tags[$i]['id']);
	return $tags;
}

/*
 * deltag
 * Deletes a given tag
 */
function deltag($tid)
{
	global $db,$tables,$mdb_conf;
	if (!(isset($_SESSION[$mdb_conf['session_key']]['user']) && ($_SESSION[$mdb_conf['session_key']]['user']['privilege'] > 0))) {
		echo "You do not have access to this feature!";
		return;
	}
	if (!$tid) {
		echo "No series specified";
		return;
	}
	$db->Execute("DELETE FROM " . $tables['tags'] . " WHERE id=" . $tid . " LIMIT 1");
	$db->Execute("DELETE FROM " . $tables['title_tag'] . " WHERE tag_id=" . $tid);
}

/*
 * prunetags
 * Remove tags not assigned to any title
 */
function prunetags()
{
	global $db,$tables;
	$taglist = taglist();
	foreach ($taglist as $tag) {
		if ($tag['count'] == 0) {
			$db->Execute("DELETE FROM " . $tables['tags'] . " WHERE id=" . $tag['id'] . " LIMIT 1");
		}
	}
}

/*
 * untag
 * Removes a tag from a title
 */
function untag($tid, $tag)
{
	global $db,$tables,$mdb_conf;
	if (!isset($_SESSION[$mdb_conf['session_key']]['user'])) {
		echo "You do not have access to this feature!";
		return;
	}
	if (!$tid) {
		echo "No series specified";
		return;
	}
	if (!$tag) {
		echo "No tag specified";
		return;
	}
	$db->Execute("DELETE FROM " . $tables['title_tag'] . " WHERE title_id=" . $tid . " AND tag_id=" . $tag);
	prunetags();
}

/*
 * userhistory
 * Retrieves download history for local user
 */
function userhistory($uid)
{
	global $db,$tables,$mdb_conf;
	if (!isset($_SESSION[$mdb_conf['session_key']]['user'])) {
		echo "You do not have access to this feature!";
		return;
	}
	if (!isset($uid)) {
		echo "No user specified!";
		return;
	}
	$ret = $db->GetArray("SELECT * FROM " . $tables['downloads'] . " WHERE uid=" . $uid . " ORDER BY time DESC");
	$size = count($ret);
	if ($size > 0) {
		for ($i = 0; $i < $size; $i++) {
			$ret2 = $db->GetRow("SELECT * FROM " . $tables['files'] . " WHERE id=" . $ret[$i]['fid'] . " LIMIT 1");
			if ($ret2)
				$ret[$i]['fileinfo'] = $ret2;
		}
	}
	return $ret;
}

/*
 * otherhistory
 * Retrieves download history for users other than local
 */
function otherhistory()
{
	global $db,$tables,$mdb_conf;
	if (!(isset($_SESSION[$mdb_conf['session_key']]['user']) && ($_SESSION[$mdb_conf['session_key']]['user']['privilege'] > 0))) {
		echo "You do not have access to this feature!";
		return;
	}
	$u = $db->GetArray("SELECT * FROM " . $tables['users'] . " WHERE id!=" . $_SESSION[$mdb_conf['session_key']]['user']['id'] . " ORDER BY username");
	$size = count($u);
	for ($i = 0; $i < $size; $i++) {
		$ret2 = userhistory($u[$i]['id']);
		if ($ret2)
			$u[$i]['downloads'] = $ret2;
	}
	return $u;
}

?>
