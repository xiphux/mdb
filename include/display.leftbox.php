<?php
/*
 *  display.leftbox.php
 *  MDB: A media database
 *  Component: Display - leftbox
 *  Displays leftbox
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

function leftbox()
{
	global $tpl, $mdb_conf, $cache;

	$key = "output_leftbox";
	if (isset($_SESSION[$mdb_conf['session_key']]['user']))
		$key .= "_" . md5($_SESSION[$mdb_conf['session_key']]['user']['username'] . $_SESSION[$mdb_conf['session_key']]['user']['privilege']);
	$out = $cache->Get($key);
	if (!$out) {
		if (isset($_SESSION[$mdb_conf['session_key']]['user']))
			$tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
		$out = $tpl->fetch("leftbox.tpl");
		$cache->Set($key, $out);
	}

	echo $out;
}

?>
