<?php
/*
 *  updatedbstatus.php
 *  MDB: A media database
 *  Component: Database update status
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

 if (shell_exec("ps ax | grep -v 'grep' | grep -c 'php include/updatedb.php'") >= 1)
 	echo "Updating";
 else
 	echo "Complete";

?>
