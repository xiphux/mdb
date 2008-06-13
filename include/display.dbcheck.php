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
	global $mdb_conf, $db, $tpl, $tables;
	if (!(isset($_SESSION[$mdb_conf['session_key']]['user']) && ($_SESSION[$mdb_conf['session_key']]['user']['privilege'] > 0))) {
		message("You do not have access to this feature!","warning");
		return;
	}
	$tpl->clear_all_assign();
	$tpl->assign("unmap",unmapped());
	if ($mdb_conf['optimize']) {
		$optables = array();
		foreach ($tables as $i => $table) {
			$optables[$table] = $db->Execute("OPTIMIZE TABLE " . $table);
		}
		$tpl->assign("optables",$optables);
	}
	$tpl->display("dbcheck.tpl");
}

 ?>
