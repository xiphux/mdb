<?php
/*
 *  display.preferences.php
 *  MDB: A media database
 *  Component: Display - preferences
 *  Edit preferences page
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

 include_once('display.message.php');
 include_once('user.getpref.php');
 include_once('util.listthemes.php');

 function preferences()
 {
 	global $mdb_conf,$tpl;
 	if (!isset($_SESSION[$mdb_conf['session_key']]['user'])) {
		message("Not valid for anonymous users!","warning");
		return;
	}
	$tpl->clear_all_assign();
	$tpl->assign("selectedtheme",getpref("theme",$mdb_conf['theme']));
	$tpl->assign("themes",listthemes());
	$tpl->display("preferences.tpl");
 }

?>
