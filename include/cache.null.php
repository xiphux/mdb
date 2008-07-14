<?php
/*
 *  cache.null.php
 *  MDB: A media database
 *  Component: Null cache
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

class MDB_Cache_Null
{
	function MDB_Cache_Null()
	{
	}

	function cachetype()
	{
		return "null";
	}

	function get($key)
	{
		return null;
	}

	function set($key, $val)
	{
		return FALSE;
	}

	function del($key)
	{
		return FALSE;
	}

	function close()
	{
	}

	function clear()
	{
		return FALSE;
	}

	function stats()
	{
		return null;
	}

}
