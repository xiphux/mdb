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

function dbcheck()
{
	global $mdb_conf, $tpl, $tables;
	if (!(isset($_SESSION[$mdb_conf['session_key']]['user']) && ($_SESSION[$mdb_conf['session_key']]['user']['privilege'] > 0))) {
		message("You do not have access to this feature!","warning");
		return;
	}
	$tpl->clear_all_assign();
	$tpl->assign("unmap",unmapped());
	if ($mdb_conf['dbmutex'] && (php_uname("s") == "Linux")) {
		$tpl->assign("dbmutexcheck",1);
		$status = DBGetOne("SELECT MAX(progress) FROM " . $tables['dbupdate']);
		if ($status && $status > 0) {
			$updating = shell_exec("ps ax | grep -v 'grep' | grep -c '" . basename($mdb_conf['phpexec']) . " include/updatedb.php'");
			if ($updating < 1) {
				DBExecute("DELETE FROM " . $tables['dbupdate'] . " WHERE progress!=0");
				$tpl->assign("dbmutexfixed",1);
			}
		}
	}
	if ($mdb_conf['optimize']) {
		$optables = array();
		foreach ($tables as $i => $table) {
			$optables[$table] = DBExecute("OPTIMIZE TABLE " . $table);
		}
		$tpl->assign("optables",$optables);
	}
	$tpl->display("dbcheck.tpl");
}

 ?>
