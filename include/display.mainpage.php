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
	global $tpl,$mdb_appstring,$tables,$mdb_conf;
	$tpl->clear_all_assign();
	$tpl->assign("banner",$mdb_appstring);

	$tmp = mdb_memcache_get("titlecount");
	if (!$tmp) {
		$tmp = DBGetOne("SELECT COUNT(id) FROM " . $tables['titles']);
		mdb_memcache_set("titlecount",$tmp);
	}
	$tpl->assign("titlecount", $tmp);

	$tmp2 = mdb_memcache_get("filecount");
	if (!$tmp2) {
		$tmp2 = DBGetOne("SELECT COUNT(id) FROM " . $tables['files']);
		mdb_memcache_set("filecount", $tmp2);
	}
	$tpl->assign("filecount", $tmp2);

	$tmp3 = mdb_memcache_get("collectionsize");
	if (!$tmp3) {
		$tmp3 = DBGetOne("SELECT SUM(size) FROM " . $tables['files']);
		mdb_memcache_set("collectionsize", $tmp3);
	}
	$tpl->assign("size", $tmp3);

	$tpl->assign("updating",updating());
	$update = mdb_memcache_get("lastupdate");
	if (!$update) {
		$update = DBGetOne("SELECT MAX(time) FROM " . $tables['dbupdate'] . " WHERE progress=0");
		if ($update)
			mdb_memcache_set("lastupdate", $update);
	}
	if ($update)
		$tpl->assign("update",$update);
	date_default_timezone_set("UTC");
	$tpl->display("main.tpl");
}

?>
