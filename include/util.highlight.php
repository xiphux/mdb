<?php
/*
 *  util.highlight.php
 *  MDB: A media database
 *  Component: Util - highlight
 *  Highlights a substring in a given string
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

 function highlight(&$string, $substr, $type = "highlight")
 {
	$string = "<span>" . $string . "</span>";
	$string = eregi_replace("(>[^<]*)(" . quotemeta($substr) . ")","\\1<span class=\"" . $type . "\">\\2</span>",$string);
	$string = eregi_replace("<span>","",$string);
	$string = substr($string,0,-7);
 }

?>
