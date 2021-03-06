<?php
/*
 *  display.mainpage.php
 *  MDB: A media database
 *  Component: Display - mainpage
 *  Displays main menu page
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

 include_once('database.updating.php');

function mainpage()
{
	global $tpl,$tables,$mdb_conf, $cache;
	$tpl->clear_all_assign();
	$tpl->assign("banner",MDB_APPSTRING);

	$tmp = $cache->Get("titlecount");
	if (!$tmp) {
		$tmp = DBGetOne("SELECT COUNT(id) FROM " . $tables['titles']);
		$cache->Set("titlecount",$tmp);
	}
	$tpl->assign("titlecount", $tmp);

	$tmp2 = $cache->Get("filecount");
	if (!$tmp2) {
		$tmp2 = DBGetOne("SELECT COUNT(id) FROM " . $tables['files']);
		$cache->Set("filecount", $tmp2);
	}
	$tpl->assign("filecount", $tmp2);

	$tmp3 = $cache->Get("collectionsize");
	if (!$tmp3) {
		$tmp3 = DBGetOne("SELECT SUM(size) FROM " . $tables['files']);
		$cache->Set("collectionsize", $tmp3);
	}
	$tpl->assign("size", $tmp3);

	$tpl->assign("updating",updating());
	$update = $cache->Get("lastupdate");
	if (!$update) {
		$update = DBGetOne("SELECT MAX(time) FROM " . $tables['dbupdate'] . " WHERE progress=0");
		if ($update)
			$cache->Set("lastupdate", $update);
	}
	if ($update)
		$tpl->assign("update",$update);
	date_default_timezone_set("UTC");
	$tpl->display("main.tpl");
}

?>
