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
	global $db,$tables;
	$q = "SELECT file FROM " . $tables['files'] . " LEFT JOIN " . $tables['file_title'] . " ON " . $tables['files'] . ".id = " . $tables['file_title'] . ".file_id WHERE " . $tables['file_title'] . ".title_id IS NULL";
	$files = $db->GetArray($q);
	if (sizeof($files) > 0)
		return $files;
	return null;
}

?>
