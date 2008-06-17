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
	$tpl->assign("titlecount",DBGetOne("SELECT COUNT(id) FROM " . $tables['titles']));
	$tpl->assign("filecount",DBGetOne("SELECT COUNT(id) FROM " . $tables['files']));
	$tpl->assign("size",DBGetOne("SELECT SUM(size) FROM " . $tables['files']));
	$tpl->assign("updating",updating());
	$update = DBGetOne("SELECT MAX(time) FROM " . $tables['dbupdate'] . " WHERE progress=0");
	if ($update)
		$tpl->assign("update",$update);
	date_default_timezone_set("UTC");
	$tpl->display("main.tpl");
}

?>
