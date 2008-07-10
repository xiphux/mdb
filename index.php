<?php
/*
 *  index.php
 *  MDB: A media database
 *  Component: Index page
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

 $mdbstarttime = microtime(true);

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

 if ($mdb_conf['debug'])
 	error_reporting(E_ALL | E_STRICT);

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
 if (isset($_GET['u']) && ($_GET['u'] === "file")) {
 	if (!(isset($_SESSION[$mdb_conf['session_key']]['user'])))
		$errorstr = "You do not have permission to use this feature!";
	else if (!$mdb_conf['download'])
		$errorstr = "File downloads have been disallowed";
 	else if (!isset($_GET['id']))
		$errorstr = "No file specified";
	else {
		$file = DBGetRow("SELECT * FROM " . $tables['files'] . " WHERE id=" . $_GET['id']);
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
				DBExecute("INSERT INTO " . $tables['downloads'] . " (ip,uid,user,fid,file,fsize) VALUES (" . DBqstr($_SERVER['REMOTE_ADDR']) . "," . $_SESSION[$mdb_conf['session_key']]['user']['id'] . "," . DBqstr($_SESSION[$mdb_conf['session_key']]['user']['username']) . "," . $file['id'] . "," . DBqstr($file['file']) . "," . $file['size'] . ")");
				mdb_memcache_delete("userhistory_" . $_SESSION[$mdb_conf['session_key']]['user']['id']);
				mdb_memcache_delete("userhistorysize_" . $_SESSION[$mdb_conf['session_key']]['user']['id']);
				mdb_memcache_delete("userlist");
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
		case "tagcloud":
			include_once('include/tag.taglist.php');
			include_once('include/display.tagcloud.php');
			tagcloud();
			break;
		case "deltag":
			include_once('include/tag.deltag.php');
			include_once('include/display.tagcloud.php');
			deltag($_POST['id']);
			tagcloud();
			break;
		case "addtag":
			include_once('include/tag.addtag.php');
			include_once('include/display.title.php');
			addtag($_GET['tid'],$_POST['tag']);
			title($_GET['tid']);
			break;
		case "untag":
			include_once('include/tag.untag.php');
			include_once('include/display.title.php');
			untag($_POST['tid'],$_POST['tag']);
			title($_POST['tid']);
			break;
		case "tag":
			include_once('include/tag.taginfo.php');
			$tpl->clear_all_assign();
			$tpl->assign("tag",taginfo($_GET['id']));
			if (isset($_SESSION[$mdb_conf['session_key']]['user']))
 				$tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
			$tpl->display("tag.tpl");
			break;
		case "file":
			include_once('include/display.message.php');
			message($errorstr,"warning");
			break;
		case "login":
			include_once('include/user.login.php');
			include_once('include/display.mainpage.php');
			login($_POST['user'],$_POST['pass']);
			mainpage();
			break;
		case "logout":
			include_once('include/display.message.php');
			include_once('include/display.mainpage.php');
			unset($_SESSION[$mdb_conf['session_key']]['user']);
			message("Logged out");
			mainpage();
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
			if (isset($_SESSION[$mdb_conf['session_key']]['user']))
 				$tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
			if ($mdb_conf['download'])
				$tpl->assign("download",TRUE);
			$tpl->display("search.tpl");
			break;
		case "title":
			include_once('include/display.title.php');
			title($_GET['id']);
			break;
		case "dbcheck":
			include_once('include/display.dbcheck.php');
			dbcheck();
			break;
		case "dbstats":
			include_once('include/display.dbstats.php');
			dbstats();
			break;
		case "database":
			include_once('include/display.database.php');
			database();
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
			userdel($_POST['uid']);
			usermanage();
			break;
		case "changeprivilege":
			include_once('include/display.usermanage.php');
			include_once('include/user.changeprivilege.php');
			changeprivilege($_POST['uid'],$_POST['privilege']);
			usermanage();
			break;
		case "preferences":
			include_once('include/display.preferences.php');
			preferences();
			break;
		case "updatepass":
			include_once('include/display.preferences.php');
			include_once('include/user.updatepass.php');
			updatepass($_POST['oldpass'],$_POST['newpass'],$_POST['newpass2']);
			preferences();
			break;
		case "changetheme":
			include_once('include/display.preferences.php');
			include_once('include/user.setpref.php');
			setpref("theme",$_POST['theme']);
			preferences();
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
 include_once('include/user.getpref.php');
 $tpl->assign("theme",getpref("theme",$mdb_conf['theme']));
 $tpl->display("header.tpl");

 if (isset($_SESSION[$mdb_conf['session_key']]['user']))
 	$tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
 $tpl->display("leftbox.tpl");

 echo $main;

 include_once('include/title.titlelist.php');
 $tpl->assign("titlelist",titlelist());
 $tpl->display("rightbox.tpl");

 include_once('include/display.footer.php');
 footer($mdbstarttime);

 ob_end_flush();

 mdb_memcache_close();

?>
