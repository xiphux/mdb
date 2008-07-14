<?php
/*
 *  cache.php
 *  MDB: A media database
 *  Component: Cache base
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

 $cache = null;
 if ($mdb_conf['cachetype'] == "memcache") {
 	include_once('cache.memcache.php');
	$cache = new MDB_Cache_Memcache($mdb_conf['memcached_address'], $mdb_conf['memcached_port'], $mdb_conf['memcached_persist']);
 } else if ($mdb_conf['cachetype'] == "eaccelerator" && function_exists("eaccelerator_get")) {
 	include_once('cache.eaccelerator.php');
	$cache = new MDB_Cache_Eaccelerator();
 } else if ($mdb_conf['cachetype'] == "filecache") {
 	include_once('cache.filecache.php');
	$cache = new MDB_Cache_Filecache($mdb_conf['filecache_dir']);
 } else {
 	include_once('cache.null.php');
	$cache = new MDB_Cache_Null();
 }

?>
