<?php
/*
 *  display.dbstats.php
 *  MDB: A media database
 *  Component: Display - dbstats
 *  Displays database statistics
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

function dbstats()
{
	global $mdb_conf,$db,$tpl,$tables,$mdb_appstring;
	$tpl->clear_all_assign();
	$tpl->assign("appstring",$mdb_appstring);
	$tpl->assign("cdate","2006");
	$tpl->assign("cauthor_email","xiphux@gmail.com");
	$tpl->assign("cauthor","Christopher Han");
	$tpl->assign("server",getenv('SERVER_NAME'));
	$tpl->assign("uname", php_uname());
	$uptime = @exec('uptime');
	if (preg_match("/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/",$uptime,$avgs)) {
		$uptime = explode(' up ', $uptime);
		$uptime = explode(',', $uptime[1]);
		$uptime = $uptime[0].', '.$uptime[1];
		$load=$avgs[1].",".$avgs[2].",".$avgs[3]."";
		$tpl->assign("uptime_days",$uptime);
		$tpl->assign("loadavg",$load);
	}
	$tpl->assign("files",$db->GetOne("SELECT COUNT(id) FROM " . $tables['files']));
	$tpl->assign("titles",$db->GetOne("SELECT COUNT(id) FROM " . $tables['titles']));
	$tpl->assign("users",$db->GetOne("SELECT COUNT(id) FROM " . $tables['users']));
	$tpl->assign("tags",$db->GetOne("SELECT COUNT(id) FROM " . $tables['tags']));
	$tpl->assign("links",$db->GetOne("SELECT COUNT(id) FROM " . $tables['links']));
	$tpl->assign("downloads",$db->GetOne("SELECT COUNT(id) FROM " . $tables['downloads']));
	$tpl->assign("dbupdate",$db->GetOne("SELECT COUNT(id) FROM " . $tables['dbupdate']));
	$dbstats = $db->GetArray("SHOW TABLE STATUS");
	$total = 0;
	$tablelist = array();
	foreach ($dbstats as $row) {
		if (in_array($row['Name'],$tables)) {
			if (isset($row['Data_length']) && isset($row['Index_length'])) {
				$t = $row['Data_length'] + $row['Index_length'];
				$row['total_size'] = $t;
				$total += $t;
			}
			$tablelist[] = $row;
		}
	}
	$tpl->assign("tablelist",$tablelist);
	$tpl->assign("dbsize",$total);
	$tpl->display("dbstats.tpl");
}

?>
