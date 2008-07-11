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

	$out = mdb_memcache_get("output_rightbox");
	if ($out) {
		echo $out;
		return;
	}

	$tpl->assign("titlelist",titlelist());
	$out = $tpl->fetch("rightbox.tpl");

	mdb_memcache_set("output_rightbox", $out);

	echo $out;
}

?>
