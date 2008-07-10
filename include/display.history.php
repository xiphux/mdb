<?php
/*
 *  display.history.php
 *  MDB: A media database
 *  Component: Display - history
 *  Displays history page
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */
include_once('include/user.userhistory.php');
include_once('include/user.userhistorysize.php');
include_once('include/user.otherhistory.php');
include_once('include/display.message.php');

function history()
{
	global $tpl, $mdb_conf;
	if (!isset($_SESSION[$mdb_conf['session_key']]['user'])) {
		message("Not valid for anonymous users!","warning");
		return;
	}
	$tpl->clear_all_assign();
	$tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
	$tpl->assign("download_log",$mdb_conf['download_log']);
	$tpl->assign("userhistory",userhistory($_SESSION[$mdb_conf['session_key']]['user']['id']));
	$tpl->assign("userhistorysize",userhistorysize($_SESSION[$mdb_conf['session_key']]['user']['id']));
	if ($_SESSION[$mdb_conf['session_key']]['user']['privilege'] > 0)
		$tpl->assign("otherhistory",otherhistory());
	$tpl->display("history.tpl");
}

?>
