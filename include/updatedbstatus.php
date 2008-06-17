<?php
/*
 *  updatedbstatus.php
 *  MDB: A media database
 *  Component: Database update status
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

 include_once('../config/mdb.conf.php');
 include_once('database.updating_dbmutex.php');
 include_once('database.updating_shellmutex.php');

 if ($mdb_conf['dbmutex']) {
 	include_once('db.php');
	if (updating_dbmutex())
 		echo "Database updating";
 	else {
		$lastupdate = DBGetOne("SELECT UNIX_TIMESTAMP(MAX(time)) FROM " . $tables['dbupdate']);
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
 	if (updating_shellmutex())
 		echo "Database updating";
 	else
 		echo "Database update complete";
 }

?>
