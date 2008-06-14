<?php
/*
 *  util.listthemes.php
 *  MDB: A media database
 *  Component: Utility - listthemes
 *  List themes
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

define('THEMEDIR','css/themes');

function listthemes()
{
	$themes = array();
	if (is_dir(THEMEDIR)) {
		if ($dh = opendir(THEMEDIR)) {
			while (($file = readdir($dh)) !== false) {
				if ((!(substr($file,0,1) == ".")) && (substr($file,-4) == ".css")) {
					$themes[] = $file;
				}
			}
		}
	}
	sort($themes);
	return $themes;
}

?>
