<?php
/*
 *  database.unmapped.php
 *  MDB: A media database
 *  Component: Database - unmapped
 *  Tests for any files that are not mapped to a title
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

function unmapped()
{
	global $tables;
	$q = "SELECT file FROM " . $tables['files'] . " WHERE tid=0";
	$files = DBGetArray($q);
	if (sizeof($files) > 0)
		return $files;
	return null;
}

?>
