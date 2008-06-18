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
	$title = titleinfo($tid);
	$tpl->clear_all_assign();
	$tpl->assign("title", $title);
	if (isset($_SESSION[$mdb_conf['session_key']]['user']))
 		$tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
	$tpl->assign("taglist",taglist());
	$tpl->display("title.tpl");
	$tpl->display("fileliststart.tpl");

	if (count($title['files']) > 0) {
		$odd = TRUE;
		$currentdir = array();
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
				$newdir = TRUE;
				$tpl->clear_all_assign();
				$tpl->assign("indentamt",(count($currentdir)-1));
				$tpl->assign("dir",TRUE);
				$tpl->assign("filename",$dir);
				$tpl->display("filelistitem.tpl");
				$odd = !$odd;
			}
			$tpl->clear_all_assign();
			if (isset($_SESSION[$mdb_conf['session_key']]['user']))
				$tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
			if ($mdb_conf['download'])
				$tpl->assign("download",TRUE);
			$tpl->assign("indentamt",count($currentdir));
			$tpl->assign("class",($odd?"odd":"even"));
			$tpl->assign("fileid",$file['id']);
			$tpl->assign("filename",$file['file']);
			$tpl->assign("filesize",$file['size']);
			$tpl->display("filelistitem.tpl");
			$odd = !$odd;
		}
	} else {
		$tpl->clear_all_assign();
		$tpl->assign("empty",TRUE);
		$tpl->display("filelistitem.tpl");
	}

	$tpl->display("filelistend.tpl");
}

?>
