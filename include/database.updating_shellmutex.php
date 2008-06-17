<?php
/*
 *  database.updating_shellmutex.php
 *  MDB: A media database
 *  Component: Database - updating_shellmutex
 *  Tests if the database is updating using shellmutex method
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

 function updating_shellmutex()
 {
 	global $mdb_conf;
	return (shell_exec("ps ax | grep -v 'grep' | grep -c '" . basename($mdb_conf['phpexec']) . " include/updatedb.php'") >= 1);
 }

?>
