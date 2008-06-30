<?php
/*
 *  database.existence.php
 *  MDB: A media database
 *  Component: Database - existence
 *  Tests for any files that do not exist
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

function existence()
{
	global $tables,$mdb_conf;
	$missingfiles = array();
	$filelist = DBGetArray("SELECT * FROM " . $tables['files'] . " ORDER BY file");
	foreach ($filelist as $i => $file) {
		if (!file_exists($mdb_conf['root'] . $file['file']))
			$missingfiles[] = $file;
	}
	if (count($missingfiles) > 0)
		return $missingfiles;
	return null;
}

?>
