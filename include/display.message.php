<?php
/*
 *  display.message.php
 *  MDB: A media database
 *  Component: Display - message
 *  Display message to user
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 */

 function message($message, $class = "highlight")
 {
 	global $tpl;
	$tpl->clear_all_assign();
	$tpl->assign("messageclass",$class);
	$tpl->assign("message",$message);
	$tpl->display("message.tpl");
 }

?>
