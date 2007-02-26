<?php
/*
 *  index.php
 *  MDB: A media database
 *  Component: Index page
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
  * Start session
  */
 session_start();

 /*
  * Start output buffering
  */
 ob_start();

 /*
  * Version definitions
  */
 $version = "v01d";
 $mdb_appstring = "MDB $version";

 /*
  * Database connection
  */
 include_once('db.inc.php');

 /*
  * Instantiate Smarty
  */
 include_once($mdb_conf['smarty_prefix'] . "Smarty.class.php");
 $tpl =& new Smarty;
 $tpl->register_modifier('size','size_readable');
 $tpl->load_filter('output','trimwhitespace');

 /*
  * Function library
  */
 include_once('mdb.lib.php');

 /*
  * Early check for file download so we
  * can skip headers
  */
 if ($_GET['u'] === "file") {
 	if (!(isset($_SESSION[$mdb_conf['session_key']]['user'])))
		$errorstr = "You do not have permission to use this feature!";
	else if (!$mdb_conf['download'])
		$errorstr = "File downloads have been disallowed";
 	else if (!isset($_GET['id']))
		$errorstr = "No file specified";
	else {
		$file = $db->GetRow("SELECT * FROM " . $tables['files'] . " WHERE id=" . $_GET['id'] . " LIMIT 1");
		if (!$file)
			$errorstr = "No such file";
		else {
			if ($mdb_conf['download_log'])
				$db->Execute("INSERT INTO " . $tables['downloads'] . " (uid,user,fid,file,fsize) VALUES (" . $_SESSION[$mdb_conf['session_key']]['user']['id'] . "," . $db->qstr($_SESSION[$mdb_conf['session_key']]['user']['username']) . "," . $file['id'] . "," . $db->qstr($file['file']) . "," . $file['size'] . ")");
			if (ini_get('zlib.output_compression'))
				ini_set('zlib.output_compression', 'Off');
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false);
			if (function_exists("finfo_open")) {
				$mgc = finfo_open(FILEINFO_MIME);
				if ($mgc) {
					header("Content-Type: " . finfo_file($mgc,$mdb_conf['root'] . $file['file']));
					finfo_close($mgc);
				} else
					header("Content-Type: application/force-download");
			} else
				header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=\"" . basename($file['file']) . "\";");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: " . @filesize($mdb_conf['root'] . $file['file']));
			set_time_limit(0);
			ob_end_flush();
			readfile_chunked($mdb_conf['root'] . $file['file']);
			exit;
		}
	}
 }

 ob_start();
 $tpl->display("mainstart.tpl");
 if (isset($_GET['u'])) {
 	switch ($_GET['u']) {
		case "taglist":
			$tpl->assign("taglist",taglist());
			$tpl->assign("tagcloudmax",$mdb_conf['tagcloudmax']);
			$tpl->assign("tagcloudmin",$mdb_conf['tagcloudmin']);
			$tpl->assign("tagcloudrange",$mdb_conf['tagcloudmax']-$mdb_conf['tagcloudmin']);
			$tpl->display("taglist.tpl");
			break;
		case "deltag":
			deltag($_GET['id']);
			echo "Tag removed";
			break;
		case "addtag":
			addtag($_GET['tid'],$_POST['tag']);
			$tpl->clear_all_assign();
			$tpl->assign("title",titleinfo($_GET['tid']));
 			$tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
			$tpl->assign("taglist",taglist());
			if ($mdb_conf['download'])
				$tpl->assign("download",TRUE);
			$tpl->display("title.tpl");
			break;
		case "untag":
			untag($_GET['tid'],$_GET['tag']);
			$tpl->clear_all_assign();
			$tpl->assign("title",titleinfo($_GET['tid']));
 			$tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
			$tpl->assign("taglist",taglist());
			if ($mdb_conf['download'])
				$tpl->assign("download",TRUE);
			$tpl->display("title.tpl");
			break;
		case "tag":
			$tpl->clear_all_assign();
			$tpl->assign("tag",taginfo($_GET['id']));
 			$tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
			$tpl->display("tag.tpl");
			break;
		case "file":
			echo $errorstr;
			break;
		case "login":
			login($_POST['user'],$_POST['pass']);
			break;
		case "logout":
			unset($_SESSION[$mdb_conf['session_key']]['user']);
			echo "Logged out";
			break;
		case "updatedb":
			updatedb();
			break;
		case "search":
			$tpl->clear_all_assign();
			$tpl->assign("search",$_POST['search']);
			$tpl->assign("results",search($_POST['search'],$_POST['criteria']));
 			$tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
			if ($mdb_conf['download'])
				$tpl->assign("download",TRUE);
			$tpl->display("search.tpl");
			break;
		case "title":
			$tpl->clear_all_assign();
			$tpl->assign("title",titleinfo($_GET['id']));
 			$tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
			$tpl->assign("taglist",taglist());
			if ($mdb_conf['download'])
				$tpl->assign("download",TRUE);
			$tpl->display("title.tpl");
			break;
		case "unmap":
			$tpl->clear_all_assign();
			$tpl->assign("unmap",unmapped());
			$tpl->display("unmapped.tpl");
			break;
		case "dbstats":
			dbstats();
			break;
		case "main":
			mainpage();
			break;
		default:
			echo "404";
			break;
	}
 } else
 	mainpage();
 $tpl->display("mainend.tpl");
 $main = ob_get_contents();
 ob_end_clean();

 $tpl->clear_all_assign();
 $title = $mdb_conf['title'];
 $tpl->assign("title",$title);
 $tpl->display("header.tpl");

 $tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
 if ($mdb_conf['dbstats'])
 	$tpl->assign("dbstats",TRUE);
 $tpl->display("leftbox.tpl");

 echo $main;

 $tpl->assign("titlelist",titlelist());
 $tpl->display("rightbox.tpl");

 $tpl->display("footer.tpl");

 ob_end_flush();
?>
