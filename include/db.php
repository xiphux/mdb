<?php
/*
 *  db.php
 *  MDB: A media database
 *  Component: Database library
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */
 include_once($mdb_conf['adodb_prefix'] . "adodb.inc.php");

$tables = array();
$tables['files'] = $mdb_conf['prefix'] . "files";
$tables['titles'] = $mdb_conf['prefix'] . "titles";
$tables['file_title'] = $mdb_conf['prefix'] . "file_title";
$tables['tags'] = $mdb_conf['prefix'] . "tags";
$tables['title_tag'] = $mdb_conf['prefix'] . "title_tag";
$tables['users'] = $mdb_conf['prefix'] . "users";
$tables['links'] = $mdb_conf['prefix'] . "links";
$tables['downloads'] = $mdb_conf['prefix'] . "downloads";
$tables['dbupdate'] = $mdb_conf['prefix'] . "dbupdate";

$db = NewADOConnection($mdb_conf['db_type']);
if ($mdb_conf['persist'])
	$db->PConnect($mdb_conf['db_host'],$mdb_conf['db_user'],$mdb_conf['db_pass'],$mdb_conf['database']);
else
	$db->Connect($mdb_conf['db_host'],$mdb_conf['db_user'],$mdb_conf['db_pass'],$mdb_conf['database']);
$db->SetFetchMode(ADODB_FETCH_ASSOC);

?>
