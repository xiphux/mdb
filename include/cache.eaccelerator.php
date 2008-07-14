<?php
/*
 *  cache.eaccelerator.php
 *  MDB: A media database
 *  Component: Eaccelerator cache class
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

class MDB_Cache_Eaccelerator
{
	var $namespace = "mdb_";
	var $cachehits = 0;
	var $cachemisses = 0;

	function MDB_Cache_Memcache()
	{
	}

	function cachetype()
	{
		return "Eaccelerator";
	}

	function get($key)
	{
		$ret = eaccelerator_get($this->namespace . $key);
		if ($ret === null)
			$this->cachemisses++;
		else
			$this->cachehits++;
		return unserialize($ret);
	}

	function set($key, $val)
	{
		$lookup = eaccelerator_get($this->namespace . "lookup");
		if ($lookup)
			$lookup = unserialize($lookup);
		else
			$lookup = array();
		$lookup[$key] = 1;
		if (eaccelerator_put($this->namespace . $key, serialize($val))) {
			return eaccelerator_put($this->namespace . "lookup", serialize($lookup));
		}
		return FALSE;
	}

	function del($key)
	{
		$lookup = eaccelerator_get($this->namespace . "lookup");
		if ($lookup) {
			$lookup = unserialize($lookup);
			unset($lookup[$key]);
		}
		if (eaccelerator_rm($this->namespace . $key)) {
			return eaccelerator_put($this->namespace . "lookup", serialize($lookup));
		}
		return FALSE;
	}

	function close()
	{
	}

	function clear()
	{
		$lookup = eaccelerator_get($this->namespace . "lookup");
		if ($lookup) {
			$lookup = unserialize($lookup);
			foreach ($lookup as $key => $val) {
				if (eaccelerator_rm($this->namespace . $key))
					unset($lookup[$key]);
			}
			if (count($lookup) > 0) {
				eaccelerator_put($this->namespace . "lookup", serialize($lookup));
				return FALSE;
			} else
				return eaccelerator_rm($this->namespace . "lookup");
		}
		return TRUE;
	}

	function stats()
	{
		$stats = array();

		$lookup = eaccelerator_get($this->namespace . "lookup");
		if ($lookup) {
			$lookup = unserialize($lookup);
			$stats['cached_objects'] = count($lookup);
		} else
			$stats['cached_objects'] = 0;

		return $stats;
	}

}

?>
