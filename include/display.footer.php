<?php
/*
 *  display.footer.php
 *  MDB: A media database
 *  Component: Display - footer
 *  Displays footer
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

function footer()
{
	global $tpl,$mdb_appstring,$tables,$mdb_conf,$querycount;
	$tpl->clear_all_assign();
	$tpl->assign("banner",$mdb_appstring);
	$tpl->assign("titlecount",DBGetOne("SELECT COUNT(id) FROM " . $tables['titles']));
	$tpl->assign("filecount",DBGetOne("SELECT COUNT(id) FROM " . $tables['files']));
	$tpl->assign("size",DBGetOne("SELECT SUM(size) FROM " . $tables['files']));
	$update = DBGetOne("SELECT MAX(time) FROM " . $tables['dbupdate'] . " WHERE progress=0");
	if ($update)
		$tpl->assign("update",$update);
	if ($mdb_conf['dbmutex']) {
		$status = DBGetOne("SELECT MAX(progress) FROM " . $tables['dbupdate']);
		if ($status && $status > 0)
			$tpl->assign("updating",TRUE);
	} else {
		if (shell_exec("ps ax | grep -v 'grep' | grep -c '" . basename($mdb_conf['phpexec']) . " include/updatedb.php'") >= 1)
			$tpl->assign("updating",TRUE);
	}
	$tpl->assign("queries",$querycount);
	date_default_timezone_set("UTC");
	$tpl->display("footer.tpl");
}

?>
