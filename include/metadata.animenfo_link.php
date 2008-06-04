<?php
/*
 *  metadata.animenfo_link.php
 *  MDB: A media database
 *  Component: Metadata - animenfo_link
 *  Returns full animenfo link from array of link data
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

 /*
  * Base animenfo url
  */
 define('ANIMENFO_BASE','http://www.animenfo.com');

 function animenfo_link($data)
 {
 	$url = ANIMENFO_BASE;
	if (!(isset($data['nfo_id']) && isset($data['nfo_type']) && isset($data['nfo_n']) && isset($data['nfo_t'])))
		return $url;
	$url .= "/" . $data['nfo_type'] . "title" . "," . $data['nfo_id'] . "," . $data['nfo_n'] . "," . $data['nfo_t'] . ".html";
	return $url;
 }

?>
