<?php
/*
 *  display.tagcloud.php
 *  MDB: A media database
 *  Component: Display - tagcloud
 *  Displays tag cloud page
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

include_once('include/tag.taglist.php');

function tagcloud()
{
	global $tpl,$mdb_conf;
	$tpl->clear_all_assign();
	$tpl->assign("taglist",taglist());
	$tpl->assign("tagcloudmax",$mdb_conf['tagcloudmax']);
	$tpl->assign("tagcloudmin",$mdb_conf['tagcloudmin']);
	$tpl->assign("tagcloudrange",$mdb_conf['tagcloudmax']-$mdb_conf['tagcloudmin']);
	$tpl->display("tagcloud.tpl");
}

?>
