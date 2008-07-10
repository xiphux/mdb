<?php
/*
 *  display.tag.php
 *  MDB: A media database
 *  Component: Display - tag
 *  Displays tag cloud page
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */
include_once('include/tag.taginfo.php');

function tag($id)
{
	global $tpl, $mdb_conf;
	$tpl->clear_all_assign();
	$tpl->assign("tag",taginfo($id));
	if (isset($_SESSION[$mdb_conf['session_key']]['user']))
 		$tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
	$tpl->display("tag.tpl");
}

?>
