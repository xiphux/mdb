<?php
/*
 *  index.php
 *  MDB: A media database
 *  Component: Index page
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

 /*
  * Start output buffering
  */
 ob_start();

 /*
  * Version definitions
  */
 $version = "v01a";
 $mdb_appstring = "MDB $version";

 /*
  * Database connection
  */
 include_once('db.inc.php');

 /*
  * Instantiate Smarty
  */
 include_once($mdb_conf['smarty_prefix'] . "Smarty.class.php");
 $tpl =& new Smarty;
 $tpl->caching = $mdb_conf['smarty_caching'];
 $tpl->cache_lifetime = $mdb_conf['smarty_cache_lifetime'];
 $tpl->load_filter('output','trimwhitespace');

 /*
  * Function library
  */
 include_once('mdb.lib.php');

 ob_start();
 $tpl->display("mainstart.tpl");
 if (isset($_GET['u'])) {
 	switch ($_GET['u']) {
		case "updatedb":
			exec("php updatedb.php 2>/dev/null >&- <&- >/dev/null &");
			echo "Database updating";
			break;
		default:
			echo "404";
			break;
	}
 } else {
 }
 $tpl->display("mainend.tpl");
 $main = ob_get_contents();
 ob_end_clean();

 $tpl->clear_all_assign();
 $title = $mdb_conf['title'];
 $tpl->assign("title",$title);
 $tpl->display("header.tpl");

 $tpl->assign("titlelist",titlelist());
 $tpl->display("sidebar.tpl");

 echo $main;

 $tpl->display("footer.tpl");

 ob_end_flush();
?>
