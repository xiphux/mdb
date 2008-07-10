<?php
/*
 *  display.searchpage.php
 *  MDB: A media database
 *  Component: Display - searchpage
 *  Displays search page
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */
include_once('include/util.search.php');

function searchpage($search, $criteria)
{
	global $tpl, $mdb_conf;
	$tpl->clear_all_assign();
	$tpl->assign("search",$search);
	$tpl->assign("results",search($search, $criteria));
	if (isset($_SESSION[$mdb_conf['session_key']]['user']))
		$tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
	if ($mdb_conf['download'])
		$tpl->assign("download",TRUE);
	$tpl->display("search.tpl");
}

?>
