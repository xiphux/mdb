<?php
/*
 *  cache.memcache.php
 *  MDB: A media database
 *  Component: Memcache functions
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

$memcached = null;
$memcached_namespace = getenv('SERVER_NAME') . "_mdb_";
$cachehits = 0;
$cachemisses = 0;

if ($mdb_conf['memcached'] && function_exists('memcache_get')) {
	if ($mdb_conf['memcached_persist'])
		$memcached = memcache_pconnect($mdb_conf['memcached_address'],$mdb_conf['memcached_port']);
	else
		$memcached = memcache_connect($mdb_conf['memcached_address'],$mdb_conf['memcached_port']);
	memcache_set_compress_threshold($memcached, 20000, 0.2);
}

function mdb_memcache_get($key)
{
	global $memcached, $memcached_namespace, $cachehits, $cachemisses;
	if (!$memcached)
		return null;
	$ret = memcache_get($memcached, $memcached_namespace . $key);
	if ($ret === FALSE)
		$cachemisses++;
	else
		$cachehits++;
	return $ret;
}

function mdb_memcache_set($key, $val)
{
	global $memcached, $memcached_namespace;
	if (!$memcached)
		return FALSE;
	return memcache_set($memcached, $memcached_namespace . $key, $val);
}

function mdb_memcache_delete($key)
{
	global $memcached, $memcached_namespace;
	if (!$memcached)
		return FALSE;
	return memcache_delete($memcached, $memcached_namespace . $key, 0);
}

function mdb_memcache_close()
{
	global $memcached;
	if ($memcached)
		memcache_close($memcached);
}

function mdb_memcache_flush()
{
	global $memcached;
	if ($memcached) {
		memcache_flush($memcached);
		return TRUE;
	}
	return FALSE;
}

?>
