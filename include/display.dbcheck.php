<?php
/*
 *  display.dbstats.php
 *  MDB: A media database
 *  Component: Display - dbcheck
 *  Database check
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

 include_once('database.unmapped.php');

function dbcheck()
{
	global $mdb_conf, $db, $tpl, $tables;
	$tpl->clear_all_assign();
	$tpl->assign("unmap",unmapped());
	if ($mdb_conf['optimize']) {
		$optables = array();
		foreach ($tables as $i => $table) {
			$optables[$i] = $db->Execute("OPTIMIZE TABLE " . $table);
		}
		$tpl->assign("optables",$optables);
	}
	$tpl->display("dbcheck.tpl");
}

 ?>
