<?php
/*
 *  updatedbstatus.php
 *  MDB: A media database
 *  Component: Database update status
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

 include_once('../config/mdb.conf.php');

 if ($mdb_conf['dbmutex']) {
 	include_once('db.php');
	$status = $db->GetOne("SELECT MAX(progress) FROM " . $tables['dbupdate']);
	if ($status && $status > 0)
 		echo "Database updating";
 	else
 		echo "Database update complete";
 } else {
 	if (shell_exec("ps ax | grep -v 'grep' | grep -c '" . $mdb_conf['phpexec'] . " include/updatedb.php'") >= 1)
 		echo "Database updating";
 	else
 		echo "Database update complete";
 }

?>
