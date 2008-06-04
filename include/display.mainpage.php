<?php
/*
 *  display.mainpage.php
 *  MDB: A media database
 *  Component: Display - mainpage
 *  Displays main menu page
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

function mainpage()
{
	global $tpl,$mdb_appstring,$db,$tables;
	$tpl->clear_all_assign();
	$tpl->assign("banner",$mdb_appstring);
	$tpl->assign("titlecount",$db->GetOne("SELECT COUNT(id) FROM " . $tables['titles']));
	$tpl->assign("filecount",$db->GetOne("SELECT COUNT(id) FROM " . $tables['files']));
	$tpl->assign("size",$db->GetOne("SELECT SUM(size) FROM " . $tables['files']));
	$tpl->display("main.tpl");
}

?>
