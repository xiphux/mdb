<?php
/*
 *  database.updating.php
 *  MDB: A media database
 *  Component: Database - updating
 *  Tests if the database is updating
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

 include_once('database.updating_dbmutex.php');
 include_once('database.updating_shellmutex.php');

function updating()
{
	global $mdb_conf;
	if ($mdb_conf['dbmutex'])
		return updating_dbmutex();
	else
		return updating_shellmutex();
}

?>
