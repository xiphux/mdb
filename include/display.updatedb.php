<?php
/*
 *  display.updatedb.php
 *  MDB: A media database
 *  Component: Display - updatedb
 *  Executes the updatedb script in the background
 *  and displays the update screen
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

 include_once('display.message.php');

 function updatedb()
 {
 	global $mdb_conf,$tpl;
	if (!(isset($_SESSION[$mdb_conf['session_key']]['user']) && ($_SESSION[$mdb_conf['session_key']]['user']['privilege'] > 0))) {
		message("You do not have access to this feature!","warning");
		return;
	}
	exec($mdb_conf['phpexec'] . " include/updatedb.php 2>/dev/null >&- <&- >/dev/null &");
	$tpl->clear_all_assign();
	$tpl->assign("interval",$mdb_conf['updatedbstatus_interval']);
	$tpl->display("updatedb.tpl");
 }

 ?>
