<?php
/*
 *  display.footer.php
 *  MDB: A media database
 *  Component: Display - footer
 *  Displays footer
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

 include_once('database.updating.php');

function footer()
{
	global $tpl,$tables,$mdb_conf,$querycount, $cache;
	$tpl->clear_all_assign();
	$tpl->assign("banner", MDB_APPSTRING);

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

	$update = $cache->Get("lastupdate");
	if (!$update) {
		$update = DBGetOne("SELECT MAX(time) FROM " . $tables['dbupdate'] . " WHERE progress=0");
		if ($update)
			$cache->Set("lastupdate", $update);
	}
	if ($update)
		$tpl->assign("update",$update);
	$tpl->assign("updating",updating());
	$tpl->assign("queries",$querycount);
	if ($cache->GetCacheType() !== XXCACHE_NULL) {
		$tpl->assign("cache", TRUE);
		$tpl->assign("cachetype", $cache->GetCacheTypeString());
		$tpl->assign("cachehits", $cache->GetCacheHits());
		$tpl->assign("cachemisses", $cache->GetCacheMisses());
	}
	date_default_timezone_set("UTC");
	$tpl->assign("exectime",round(microtime(true)-MDB_START_TIME,8));
	$tpl->display("footer.tpl");
}

?>
