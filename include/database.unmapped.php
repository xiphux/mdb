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
	$temp = null;
	$unmap = array();
	$files = $db->GetArray("SELECT * FROM " . $tables['files']);
	foreach ($files as $i => $file) {
		$temp = $db->GetArray("SELECT * FROM " . $tables['file_title'] . " WHERE id=" . $file['id'] . " LIMIT 1");
		if (sizeof($temp) < 1)
			$unmap[] = $file;
	}
	if (sizeof($unmap) > 0)
		return $unmap;
	return null;
}

?>
