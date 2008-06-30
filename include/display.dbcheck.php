<?php
/*
 *  display.dbcheck.php
 *  MDB: A media database
 *  Component: Display - dbcheck
 *  Database check
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

 include_once('display.message.php');
 include_once('database.unmapped.php');
 include_once('database.existence.php');
 include_once('database.updating_dbmutex.php');
 include_once('database.updating_shellmutex.php');

function dbcheck()
{
	global $mdb_conf, $tpl, $tables;
	if (!(isset($_SESSION[$mdb_conf['session_key']]['user']) && ($_SESSION[$mdb_conf['session_key']]['user']['privilege'] > 0))) {
		message("You do not have access to this feature!","warning");
		return;
	}
	$tpl->clear_all_assign();
	$tpl->assign("unmap",unmapped());
	$tpl->assign("exist",existence());
	if ($mdb_conf['dbmutex'] && (php_uname("s") == "Linux")) {
		$tpl->assign("dbmutexcheck",1);
		if (updating_dbmutex() && !updating_shellmutex()) {
			DBExecute("DELETE FROM " . $tables['dbupdate'] . " WHERE progress!=0");
			$tpl->assign("dbmutexfixed",1);
		}
	}
	if ($mdb_conf['optimize']) {
		$optables = DBGetArray("OPTIMIZE TABLE " . implode(",",$tables));
		$tpl->assign("optables",$optables);
	}
	$tpl->display("dbcheck.tpl");
}

 ?>
