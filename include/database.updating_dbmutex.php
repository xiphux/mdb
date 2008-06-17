<?php
/*
 *  database.updating_dbmutex.php
 *  MDB: A media database
 *  Component: Database - updating_dbmutex
 *  Tests if the database is updating using dbmutex method
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

function updating_dbmutex()
{
	global $tables;
 	$status = DBGetOne("SELECT MAX(progress) FROM " . $tables['dbupdate']);
	return ($status && $status > 0);
}

?>
