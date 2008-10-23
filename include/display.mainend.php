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
	global $tpl, $cache;

	$out = $cache->Get("output_mainend");
	if (!$out) {
		$out = $tpl->fetch("mainend.tpl");
		$cache->Set("output_mainend", $out);
	}

	echo $out;
}

?>
