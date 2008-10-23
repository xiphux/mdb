<?php
/*
 *  display.usermanage.php
 *  MDB: A media database
 *  Component: Display - usermanage
 *  User management
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

 include_once('display.message.php');

function usermanage()
{
	global $mdb_conf, $tpl, $tables, $cache;
	if (!(isset($_SESSION[$mdb_conf['session_key']]['user']) && ($_SESSION[$mdb_conf['session_key']]['user']['privilege'] > 0))) {
		message("You do not have access to this feature!","warning");
		return;
	}
	
	$users = $cache->Get("userlist");
	if (!$users) {
		$users = DBGetArray("SELECT " . $tables['users'] . ".id, " . $tables['users'] . ".username, " . $tables['users'] . ".privilege, SUM(" . $tables['downloads'] . ".fsize) AS size FROM " . $tables['users'] . " LEFT JOIN " . $tables['downloads'] . " ON " . $tables['users'] . ".id=" . $tables['downloads'] . ".uid GROUP BY " . $tables['users'] . ".id ORDER BY username");
		if ($users)
			$cache->Set("userlist", $users);
	}

	$tpl->clear_all_assign();
	$tpl->assign("users",$users);
	$tpl->assign("currentid",$_SESSION[$mdb_conf['session_key']]['user']['id']);
	$tpl->display("usermanage.tpl");
}

?>
