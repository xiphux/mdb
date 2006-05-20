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
  * Start session
  */
 session_start();

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
		case "login":
			login($_POST['user'],$_POST['pass']);
			break;
		case "logout":
			unset($_SESSION[$mdb_conf['session_key']]['user']);
			echo "Logged out";
			break;
		case "updatedb":
			updatedb();
			break;
		case "search":
			$tpl->clear_all_assign();
			$tpl->assign("search",$_POST['search']);
			$tpl->assign("results",search($_POST['search'],$_POST['criteria']));
 			$tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
			$tpl->display("search.tpl");
			break;
		case "title":
			$tpl->clear_all_assign();
			$tpl->assign("title",titleinfo($_GET['id']));
 			$tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
			$tpl->display("title.tpl");
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
 $tpl->assign("user",$_SESSION[$mdb_conf['session_key']]['user']);
 $tpl->display("sidebar.tpl");

 echo $main;

 $tpl->display("footer.tpl");

 ob_end_flush();
?>
