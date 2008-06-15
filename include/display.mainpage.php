<?php
/*
 *  display.mainpage.php
 *  MDB: A media database
 *  Component: Display - mainpage
 *  Displays main menu page
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

function mainpage()
{
	global $tpl,$mdb_appstring,$db,$tables,$mdb_conf;
	$tpl->clear_all_assign();
	$tpl->assign("banner",$mdb_appstring);
	$tpl->assign("titlecount",$db->GetOne("SELECT COUNT(id) FROM " . $tables['titles']));
	$tpl->assign("filecount",$db->GetOne("SELECT COUNT(id) FROM " . $tables['files']));
	$tpl->assign("size",$db->GetOne("SELECT SUM(size) FROM " . $tables['files']));
	$update = $db->GetOne("SELECT MAX(time) FROM " . $tables['dbupdate'] . " WHERE progress=0");
	if ($mdb_conf['dbmutex']) {
		$status = $db->GetOne("SELECT MAX(progress) FROM " . $tables['dbupdate']);
		if ($status && $status > 0)
			$tpl->assign("updating",TRUE);
	} else {
		if (shell_exec("ps ax | grep -v 'grep' | grep -c '" . $mdb_conf['phpexec'] . " include/updatedb.php'") >= 1)
			$tpl->assign("updating",TRUE);
	}
	if ($update)
		$tpl->assign("update",$update);
	date_default_timezone_set("UTC");
	$tpl->display("main.tpl");
}

?>
