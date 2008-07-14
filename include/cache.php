<?php
/*
 *  cache.php
 *  MDB: A media database
 *  Component: Cache base
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

 $cache = null;
 if ($mdb_conf['memcached']) {
 	include_once('cache.memcache.php');
	$cache = new MDB_Cache_Memcache($mdb_conf['memcached_address'], $mdb_conf['memcached_port'], $mdb_conf['memcached_persist']);
 } else {
 	include_once('cache.null.php');
	$cache = new MDB_Cache_Null();
 }

?>
