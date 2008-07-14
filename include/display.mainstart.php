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
	global $tpl, $cache;

	$out = $cache->get("output_mainstart");
	if (!$out) {
		$out = $tpl->fetch("mainstart.tpl");
		$cache->set("output_mainstart", $out);
	}

	echo $out;
}

?>
