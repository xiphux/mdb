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
 	include_once('include/util.filedownload.php');
 	$errorstr = filedownload((isset($_GET['id']) ? $_GET['id'] : null));
	if ($errorstr === FALSE)
		exit;
 }

 ob_start();

 include_once('include/display.mainstart.php');
 mainstart();

 if (isset($_GET['u'])) {
 	switch ($_GET['u']) {
		case "history":
			include_once('include/display.history.php');
			history();
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
			include_once('include/display.tag.php');
			tag($_GET['id']);
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
			include_once('include/display.searchpage.php');
			searchpage($_POST['search'], $_POST['criteria']);
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
			useradd($_POST['user'],$_POST['pass'],(isset($_POST['admin'])?TRUE:FALSE));
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
			message("No such page","warning");
			break;
	}
 } else {
	include_once('include/display.mainpage.php');
 	mainpage();
 }

 include_once('include/display.mainend.php');
 mainend();

 $main = ob_get_contents();
 ob_end_clean();

 include_once('include/display.pageheader.php');
 pageheader();

 include_once('include/display.leftbox.php');
 leftbox();

 echo $main;

 include_once('include/display.rightbox.php');
 rightbox();

 include_once('include/display.footer.php');
 footer($mdbstarttime);

 ob_end_flush();

 mdb_memcache_close();

?>
