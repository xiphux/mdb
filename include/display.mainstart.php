<?php
/*
 *  display.mainstart.php
 *  MDB: A media database
 *  Component: Display - mainstart
 *  Displays start of main box
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

function mainstart()
{
	global $tpl;

	$out = mdb_memcache_get("output_mainstart");
	if (!$out) {
		$out = $tpl->fetch("mainstart.tpl");
		mdb_memcache_set("output_mainstart", $out);
	}

	echo $out;
}

?>
