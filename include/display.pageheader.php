<?php
/*
 *  display.pageheader.php
 *  MDB: A media database
 *  Component: Display - pageheader
 *  Displays page header
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */
include_once('include/user.getpref.php');

function pageheader()
{
	global $tpl, $mdb_conf, $cache;

	$title = $mdb_conf['title'];
	$theme = getpref("theme",$mdb_conf['theme']);
	$key = "output_pageheader_" . md5($title . $theme);

	$out = $cache->get($key);
	if (!$out) {
		$tpl->clear_all_assign();
		$tpl->assign("title", $title);
		$tpl->assign("theme", $theme);
		$out = $tpl->fetch("header.tpl");
		$cache->set($key, $out);
	}

	echo $out;
}

?>
