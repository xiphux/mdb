<?php
/*
 *  display.tag.php
 *  MDB: A media database
 *  Component: Display - tag
 *  Displays tag cloud page
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */
include_once('include/tag.taginfo.php');

function tag($id)
{
	global $tpl, $mdb_conf;

	$key = "output_tag_" . $id;
	if (isset($_SESSION[$mdb_conf['session_key']]['user']) && ($_SESSION[$mdb_conf['session_key']]['user']['privilege'] > 0))
		$key .= "_priv";

	$out = mdb_memcache_get($key);
	if (!$out) {
		$tpl->clear_all_assign();
		$tpl->assign("tag",taginfo($id));
		if (isset($_SESSION[$mdb_conf['session_key']]['user']))
			$tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
		$out = $tpl->fetch("tag.tpl");
		mdb_memcache_set($key, $out);
	}

	echo $out;
}

?>
