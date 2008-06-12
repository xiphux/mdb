<?php
/*
 *  display.usermanage.php
 *  MDB: A media database
 *  Component: Display - usermanage
 *  User management
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

 include_once('user.userhistorysize.php');

function usermanage()
{
	global $mdb_conf, $db, $tpl, $tables;
	if (!(isset($_SESSION[$mdb_conf['session_key']]['user']) && ($_SESSION[$mdb_conf['session_key']]['user']['privilege'] > 0))) {
		echo "You do not have access to this feature!";
		return;
	}
	$users = $db->GetArray("SELECT id,username,privilege FROM " . $tables['users']);
	foreach ($users as $i => $user)
		$users[$i]['size'] = userhistorysize($user['id']);
	$tpl->clear_all_assign();
	$tpl->assign("users",$users);
	$tpl->assign("currentid",$_SESSION[$mdb_conf['session_key']]['user']['id']);
	$tpl->display("usermanage.tpl");
}

?>
