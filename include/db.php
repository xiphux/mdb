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
$tables['preferences'] = $mdb_conf['prefix'] . "preferences";

$db = NewADOConnection($mdb_conf['db_type']);
if ($mdb_conf['persist'])
	$db->PConnect($mdb_conf['db_host'],$mdb_conf['db_user'],$mdb_conf['db_pass'],$mdb_conf['database']);
else
	$db->Connect($mdb_conf['db_host'],$mdb_conf['db_user'],$mdb_conf['db_pass'],$mdb_conf['database']);
$db->SetFetchMode(ADODB_FETCH_ASSOC);

$querycount = 0;

function DBExecute($sql,$inputarr=false)
{
	global $db,$querycount,$mdb_conf;
	$querycount++;
	$ret = $db->Execute($sql,$inputarr);
	if ($mdb_conf['adodbcache'])
		$db->CacheFlush();
	return $ret;
}

function DBqstr($s,$magic_quotes_enabled=false)
{
	global $db;
	return $db->qstr($s,$magic_quotes_enabled);
}

function DBGetOne($sql)
{
	global $db,$querycount;
	$querycount++;
	return $db->GetOne($sql);
}

function DBGetRow($sql)
{
	global $db,$querycount;
	$querycount++;
	return $db->GetRow($sql);
}

function DBGetArray($sql,$inputarr=false)
{
	global $db,$querycount;
	$querycount++;
	return $db->GetArray($sql,$inputarr);
}

function DBInsertID()
{
	global $db,$querycount;
	$querycount++;
	return $db->Insert_ID();
}

function DBPrepare($sql)
{
	global $db,$querycount;
	$querycount++;
	return $db->Prepare($sql);
}

function DBStartTrans()
{
	global $db,$querycount;
	$querycount++;
	return $db->StartTrans();
}

function DBCompleteTrans($autoComplete=true)
{
	global $db,$querycount;
	$querycount++;
	return $db->CompleteTrans($autoComplete);
}

function DBErrorMsg()
{
	global $db;
	return $db->ErrorMsg();
}

?>
