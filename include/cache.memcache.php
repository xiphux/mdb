<?php
/*
 *  cache.memcache.php
 *  MDB: A media database
 *  Component: Memcache class
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

class MDB_Cache_Memcache
{
	var $memcache = null;
	var $memcache_namespace = "";
	var $cachehits = 0;
	var $cachemisses = 0;
	var $compressmin = 20000;
	var $compressratio = 0.2;

	function MDB_Cache_Memcache($addr, $port, $persist)
	{
		if ($persist)
			$this->memcache = memcache_pconnect($addr, $port);
		else
			$this->memcache = memcache_connect($addr, $port);
		memcache_set_compress_threshold($this->memcache, $this->compressmin, $this->compressratio);
		$this->memcache_namespace = getenv('SERVER_NAME') . "_mdb_";
	}

	function cachetype()
	{
		return "Memcache";
	}

	function get($key)
	{
		if (!$this->memcache)
			return null;
		$ret = memcache_get($this->memcache, $this->memcache_namespace . $key);
		if ($ret === FALSE)
			$this->cachemisses++;
		else
			$this->cachehits++;
		return $ret;
	}

	function set($key, $val)
	{
		if (!$this->memcache)
			return FALSE;
		return memcache_set($this->memcache, $this->memcache_namespace . $key, $val);
	}

	function del($key)
	{
		if (!$this->memcache)
			return FALSE;
		return memcache_delete($this->memcache, $this->memcache_namespace . $key, 0);
	}

	function close()
	{
		if ($this->memcache)
			memcache_close($this->memcache);
	}

	function clear()
	{
		if ($this->memcache) {
			memcache_flush($this->memcache);
			return TRUE;
		}
		return FALSE;
	}

	function stats()
	{
		if ($this->memcache)
			return memcache_get_stats($this->memcache);
		return null;
	}
}

?>
