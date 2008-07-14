<?php
/*
 *  cache.filecache.php
 *  MDB: A media database
 *  Component: Filecache class
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

class MDB_Cache_Filecache
{
	var $cachedir = "";
	var $cacheext = ".cache";
	var $cachehits = 0;
	var $cachemisses = 0;

	function MDB_Cache_Filecache($dir)
	{
		$this->cachedir = $dir;
	}

	function cachetype()
	{
		return "Filecache";
	}

	function get($key)
	{
		$file = $this->cachedir . $key . $this->cacheext;
		$data = @file_get_contents($file);
		if ($data === FALSE) {
			$this->cachemisses++;
			return null;
		}
		$uns = unserialize($data);
		if ($uns === FALSE) {
			$this->cachemisses++;
			return null;
		}
		$this->cachehits++;
		return $uns;
	}
	
	function set($key, $val)
	{
		return file_put_contents($this->cachedir . $key . $this->cacheext, serialize($val));
	}

	function del($key)
	{
		$file = $this->cachedir . $key . $this->cacheext;
		@chmod($file, 0777);
		@unlink($file);
	}

	function close()
	{
	}

	function clear()
	{
		$dh = opendir($this->cachedir);
		while (($file = readdir($dh)) !== FALSE) {
			if (($file != ".") && ($file != "..") && (substr($file,(strlen($this->cacheext)*-1)) == $this->cacheext)) {
				@chmod($this->cachedir . $file, 0777);
				@unlink($this->cachedir . $file);
			}
		}
		closedir($dh);
		return TRUE;
	}

}

?>
