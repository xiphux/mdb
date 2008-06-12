<?php
/*
 *  display.changepass.php
 *  MDB: A media database
 *  Component: Display - changepass
 *  Change password prompt
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

 include_once('display.message.php');

 function changepass()
 {
 	global $mdb_conf,$tpl;
 	if (!isset($_SESSION[$mdb_conf['session_key']]['user'])) {
		message("Not valid for anonymous users!","warning");
		return;
	}
	$tpl->clear_all_assign();
	$tpl->display("changepass.tpl");
 }

?>
