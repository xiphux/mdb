<?php
/*
 *  display.changepass.php
 *  MDB: A media database
 *  Component: Display - title
 *  Display title
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

include_once('include/title.titleinfo.php');
include_once('include/tag.taglist.php');

function title($tid)
{
	global $mdb_conf,$tpl;

	$key = "output_title_" . $tid;
	if (isset($_SESSION[$mdb_conf['session_key']]['user'])) {
		$key .= "_user";
		if ($mdb_conf['download'])
			$key .= "_dl";
	}
	$out = mdb_memcache_get($key);
	if ($out) {
		echo $out;
		return;
	}

	$title = titleinfo($tid);

	$out = "";

	$tpl->clear_all_assign();
	$tpl->assign("title", $title);
	$tpl->assign("letter", substr(strtolower(trim($title['title'])),0,1));
	if (isset($_SESSION[$mdb_conf['session_key']]['user']))
 		$tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
	$tpl->assign("taglist",taglist());
	$out .= $tpl->fetch("title.tpl");
	$out .= $tpl->fetch("fileliststart.tpl");

	if (count($title['files']) > 0) {
		$odd = TRUE;
		$currentdir = array();
		$allowed = "/[^a-z0-9\\/\\.\\-\\_\\\\]/i"; 
		foreach ($title['files'] as $file) {
			while ((count($currentdir) > 0) && strpos($file['file'],implode("/",$currentdir)) === false) {
				array_pop($currentdir);
			}
			if (count($currentdir) > 0)
				$file['file'] = substr($file['file'],strlen(implode("/",$currentdir))+1);
			while (($pos = strpos($file['file'],"/")) !== false) {
				$dir = substr($file['file'],0,$pos);
				$currentdir[] = $dir;
				$file['file'] = substr($file['file'],$pos+1);
				$tpl->clear_all_assign();
				$tpl->assign("indentamt",(count($currentdir)-1));
				$tpl->assign("dir",TRUE);
				$tpl->assign("filename",$dir);
				if (count($currentdir) > 1);
					$tpl->assign("itemid",preg_replace($allowed,"_",implode("/",array_slice($currentdir,0,count($currentdir)-1))));
				$tpl->assign("subid",preg_replace($allowed,"_",implode("/",$currentdir)));
				$out .= $tpl->fetch("filelistitem.tpl");
				$tpl->clear_all_assign();
				$odd = !$odd;
			}
			$tpl->clear_all_assign();
			if (isset($_SESSION[$mdb_conf['session_key']]['user']))
				$tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
			if ($mdb_conf['download'])
				$tpl->assign("download",TRUE);
			$tpl->assign("indentamt",count($currentdir));
			if (count($currentdir) > 0)
				$tpl->assign("itemid",preg_replace($allowed,"_",implode("/",$currentdir)));
			$tpl->assign("class",($odd?"odd":"even"));
			$tpl->assign("fileid",$file['id']);
			$tpl->assign("filename",$file['file']);
			$tpl->assign("filesize",$file['size']);
			$out .= $tpl->fetch("filelistitem.tpl");
			$odd = !$odd;
		}
	} else {
		$tpl->clear_all_assign();
		$tpl->assign("empty",TRUE);
		$out .= $tpl->fetch("filelistitem.tpl");
	}

	$out .= $tpl->fetch("filelistend.tpl");

	mdb_memcache_set($key, $out);

	echo $out;
}

?>
