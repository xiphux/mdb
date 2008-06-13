<?php
/*
 *  display.changepass.php
 *  MDB: A media database
 *  Component: Display - title
 *  Display title
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

include_once('include/title.titleinfo.php');
include_once('include/tag.taglist.php');

function title($tid)
{
	global $mdb_conf,$tpl;
	$tpl->clear_all_assign();
	$tpl->assign("title",titleinfo($tid));
 	$tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
	$tpl->assign("taglist",taglist());
	if ($mdb_conf['download'])
		$tpl->assign("download",TRUE);
	$tpl->display("title.tpl");
}

?>
