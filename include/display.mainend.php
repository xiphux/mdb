<?php
/*
 *  display.mainend.php
 *  MDB: A media database
 *  Component: Display - mainend
 *  Displays end of main box
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

function mainend()
{
	global $tpl;

	$out = mdb_memcache_get("output_mainend");
	if (!$out) {
		$out = $tpl->fetch("mainend.tpl");
		mdb_memcache_set("output_mainend", $out);
	}

	echo $out;
}

?>
