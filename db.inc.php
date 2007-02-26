<?php
/*
 *  db.inc.php
 *  MDB: A media database
 *  Component: Database library
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Library General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 */
 include_once('config.inc.php');
 include_once("adodb/adodb.inc.php");

$tables = array();
$tables['files'] = $mdb_conf['prefix'] . "files";
$tables['titles'] = $mdb_conf['prefix'] . "titles";
$tables['file_title'] = $mdb_conf['prefix'] . "file_title";
$tables['tags'] = $mdb_conf['prefix'] . "tags";
$tables['title_tag'] = $mdb_conf['prefix'] . "title_tag";
$tables['users'] = $mdb_conf['prefix'] . "users";
$tables['animenfo'] = $mdb_conf['prefix'] . "animenfo";
$tables['downloads'] = $mdb_conf['prefix'] . "downloads";

$db = NewADOConnection($mdb_conf['db_type']);
if ($mdb_conf['persist'])
	$db->PConnect($mdb_conf['db_host'],$mdb_conf['db_user'],$mdb_conf['db_pass'],$mdb_conf['database']);
else
	$db->Connect($mdb_conf['db_host'],$mdb_conf['db_user'],$mdb_conf['db_pass'],$mdb_conf['database']);
$db->SetFetchMode(ADODB_FETCH_ASSOC);

?>
