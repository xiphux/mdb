<?php
/*
 *  display.rightbox.php
 *  MDB: A media database
 *  Component: Display - rightbox
 *  Displays right box
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */
include_once('include/title.titlelist.php');

function rightbox()
{
	global $tpl;
	$tpl->assign("titlelist",titlelist());
	$tpl->display("rightbox.tpl");
}

?>
