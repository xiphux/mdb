<?php
/*
 *  display.database.php
 *  MDB: A media database
 *  Component: Display - database
 *  Database operations
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

 include_once('display.message.php');

 function database()
 {
	global $mdb_conf, $tpl;
	if (!(isset($_SESSION[$mdb_conf['session_key']]['user']) && ($_SESSION[$mdb_conf['session_key']]['user']['privilege'] > 0))) {
		message("You do not have access to this feature!","warning");
		return;
	}
	$tpl->clear_all_assign();
	$tpl->display("database.tpl");
 }

?>
