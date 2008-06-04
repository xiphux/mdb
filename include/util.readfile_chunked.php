<?php
/*
 *  util.readfile_chunked.php
 *  MDB: A media database
 *  Component: Util - readfile_chunked
 *  Reads a file and sends it to the browser in chunks
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

 function readfile_chunked($filename) {
 	$chunksize = 1 * (1024 * 1024);
	$buffer = '';
	$handle = fopen($filename,'rb');
	if ($handle === false)
		return false;
	while (!feof($handle)) {
		$buffer = fread($handle,$chunksize);
		echo $buffer;
		ob_flush();
		flush();
	}
	return fclose($handle);
}

?>
