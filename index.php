<?php
/*
 *  index.php
 *  MDB: A media database
 *  Component: Index page
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
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
 include_once('include/version.php');

 /*
  * Database connection
  */
 include_once('config/mdb.conf.php');
 include_once('include/db.php');

 /*
  * Instantiate Smarty
  */
 include_once($mdb_conf['smarty_prefix'] . "Smarty.class.php");
 $tpl =& new Smarty;
 include_once('include/util.size_readable.php');
 $tpl->register_modifier('size','size_readable');
 $tpl->load_filter('output','trimwhitespace');

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
			if (ini_get('zlib.output_compression'))
				ini_set('zlib.output_compression', 'Off');
			header("Content-Description: " . basename($file['file']));
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
			header("Expires: 0");
			header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
			header("Pragma: public");
			header("Content-Length: " . @filesize($mdb_conf['root'] . $file['file']));
			set_time_limit(0);
			ob_clean();
			flush();
			if ($mdb_conf['download_log']) {
				$db->Execute("INSERT INTO " . $tables['downloads'] . " (ip,uid,user,fid,file,fsize) VALUES (" . $db->qstr($_SERVER['REMOTE_ADDR']) . "," . $_SESSION[$mdb_conf['session_key']]['user']['id'] . "," . $db->qstr($_SESSION[$mdb_conf['session_key']]['user']['username']) . "," . $file['id'] . "," . $db->qstr($file['file']) . "," . $file['size'] . ")");
			}
			readfile($mdb_conf['root'] . $file['file']);
			exit;
		}
	}
 }

 ob_start();
 $tpl->display("mainstart.tpl");
 if (isset($_GET['u'])) {
 	switch ($_GET['u']) {
		case "history":
			include_once('include/user.userhistory.php');
			include_once('include/user.userhistorysize.php');
			include_once('include/user.otherhistory.php');
			if (!isset($_SESSION[$mdb_conf['session_key']]['user'])) {
				include_once('include/display.message.php');
				message("Not valid for anonymous users!","warning");
			} else {
				$tpl->clear_all_assign();
				$tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
				$tpl->assign("download_log",$mdb_conf['download_log']);
				$tpl->assign("userhistory",userhistory($_SESSION[$mdb_conf['session_key']]['user']['id']));
				$tpl->assign("userhistorysize",userhistorysize($_SESSION[$mdb_conf['session_key']]['user']['id']));
				if ($_SESSION[$mdb_conf['session_key']]['user']['privilege'] > 0)
					$tpl->assign("otherhistory",otherhistory());
				$tpl->display("history.tpl");
			}
			break;
		case "taglist":
			include_once('include/tag.taglist.php');
			$tpl->assign("taglist",taglist());
			$tpl->assign("tagcloudmax",$mdb_conf['tagcloudmax']);
			$tpl->assign("tagcloudmin",$mdb_conf['tagcloudmin']);
			$tpl->assign("tagcloudrange",$mdb_conf['tagcloudmax']-$mdb_conf['tagcloudmin']);
			$tpl->display("taglist.tpl");
			break;
		case "deltag":
			include_once('include/tag.deltag.php');
			include_once('include/display.message.php');
			deltag($_GET['id']);
			message("Tag removed");
			break;
		case "addtag":
			include_once('include/title.titleinfo.php');
			include_once('include/tag.taglist.php');
			include_once('include/tag.addtag.php');
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
			include_once('include/title.titleinfo.php');
			include_once('include/tag.untag.php');
			include_once('include/tag.taglist.php');
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
			include_once('include/tag.taginfo.php');
			$tpl->clear_all_assign();
			$tpl->assign("tag",taginfo($_GET['id']));
 			$tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
			$tpl->display("tag.tpl");
			break;
		case "file":
			include_once('include/display.message.php');
			message($errorstr,"warning");
			break;
		case "login":
			include_once('include/user.login.php');
			login($_POST['user'],$_POST['pass']);
			break;
		case "logout":
			include_once('include/display.message.php');
			unset($_SESSION[$mdb_conf['session_key']]['user']);
			message("Logged out");
			break;
		case "updatedb":
			include_once('include/display.updatedb.php');
			updatedb();
			break;
		case "search":
			include_once('include/util.search.php');
			$tpl->clear_all_assign();
			$tpl->assign("search",$_POST['search']);
			$tpl->assign("results",search($_POST['search'],$_POST['criteria']));
 			$tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
			if ($mdb_conf['download'])
				$tpl->assign("download",TRUE);
			$tpl->display("search.tpl");
			break;
		case "title":
			include_once('include/tag.taglist.php');
			include_once('include/title.titleinfo.php');
			$tpl->clear_all_assign();
			$tpl->assign("title",titleinfo($_GET['id']));
 			$tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
			$tpl->assign("taglist",taglist());
			if ($mdb_conf['download'])
				$tpl->assign("download",TRUE);
			$tpl->display("title.tpl");
			break;
		case "dbcheck":
			include_once('include/display.dbcheck.php');
			dbcheck();
			break;
		case "dbstats":
			include_once('include/display.dbstats.php');
			dbstats();
			break;
		case "main":
			include_once('include/display.mainpage.php');
			mainpage();
			break;
		case "usermanage":
			include_once('include/display.usermanage.php');
			usermanage();
			break;
		case "useradd":
			include_once('include/display.usermanage.php');
			include_once('include/user.useradd.php');
			useradd($_POST['user'],$_POST['pass'],$_POST['admin']);
			usermanage();
			break;
		case "userdel":
			include_once('include/display.usermanage.php');
			include_once('include/user.userdel.php');
			userdel($_GET['uid']);
			usermanage();
			break;
		case "changeprivilege":
			include_once('include/display.usermanage.php');
			include_once('include/user.changeprivilege.php');
			changeprivilege($_GET['uid'],$_GET['privilege']);
			usermanage();
			break;
		case "changepass":
			include_once('include/display.changepass.php');
			changepass();
			break;
		case "updatepass":
			include_once('include/display.changepass.php');
			include_once('include/user.updatepass.php');
			updatepass($_POST['oldpass'],$_POST['newpass'],$_POST['newpass2']);
			changepass();
			break;
		default:
			include_once('include/display.message.php');
			message("404","warning");
			break;
	}
 } else {
	include_once('include/display.mainpage.php');
 	mainpage();
 }
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

 include_once('include/title.titlelist.php');
 $tpl->assign("titlelist",titlelist());
 $tpl->display("rightbox.tpl");

 $tpl->display("footer.tpl");

 ob_end_flush();
?>
