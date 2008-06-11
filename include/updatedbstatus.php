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
 	else {
		$lastupdate = $db->GetOne("SELECT UNIX_TIMESTAMP(MAX(time)) FROM " . $tables['dbupdate']);
		if ($lastupdate) {
			$diff = time() - $lastupdate;
			if (($diff < $mdb_conf['dbupdate_wait']) && (($diff * 1000) > ($mdb_conf['updatedbstatus_interval'] * 2))) {
				echo "Last update was " . date("r",$lastupdate) . ", " . $diff . " seconds ago.  Update interval minimum is set to " . $mdb_conf['dbupdate_wait'] . " seconds.";
				exit;
			}
		}
 		echo "Database update complete";
	}
 } else {
 	if (shell_exec("ps ax | grep -v 'grep' | grep -c '" . $mdb_conf['phpexec'] . " include/updatedb.php'") >= 1)
 		echo "Database updating";
 	else
 		echo "Database update complete";
 }

?>
