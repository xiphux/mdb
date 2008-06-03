<?php
/*
 *  mdb.conf.php
 *  MDB: A media database
 *  Component: Configuration
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

/*
 * root
 * Root directory
 * Don't forget trailing slash!
 */
$mdb_conf['root'] = "/storage/";

/*
 * title
 * The string that will be used as the page time
 * The variable '$mdb_appstring' will expand to
 * the name (MDB) and version
 */
$mdb_conf['title'] = "centraldogma :: $mdb_appstring";

/*
 * smarty_prefix
 * This is the prefix where smarty is installed.
 * (including trailing slash)
 * If an absolute (starts with /) path is given,
 * Smarty.class.php will be searched for in that directory.
 * If a relative (doesn't start with /) path is given,
 * that subdirectory inside the php include dirs will be
 * searched.  So, for example, if you specify the path as
 * "/usr/share/Smarty/" then the script will look for
 * /usr/share/Smarty/Smarty.class.php.
 * If you specify the path as "smarty/" then it will search
 * the include directories in php.ini's include_path directive,
 * so it would search in places like /usr/share/php and /usr/lib/php:
 * /usr/share/php/smarty/Smarty.class.php,
 * /usr/lib/php/smarty/Smarty.class.php, etc.
 * Leave blank to just search in the root of the php include directories
 * like /usr/share/php/Smarty.class.php, /usr/lib/php/Smarty.class.php, etc.
 */
$mdb_conf['smarty_prefix'] = "smarty/";

/*
 * adodb_prefix
 * This is the prefix where adodb is installed.
 * Behaves the same way as smarty_prefix, but specifies
 * location of adodb_inc.php.
 */
$mdb_conf['adodb_prefix'] = "adodb/";

 /*
  * Database connection settings
  */
 $mdb_conf['db_type'] = "mysqli";
 $mdb_conf['db_host'] = "localhost";
 $mdb_conf['db_user'] = "mdb";
 $mdb_conf['db_pass'] = "G9.S4NTyP23xnFCq";
 $mdb_conf['database'] = "mdb";

 /*
  * Persistent database connection
  * Persistent database conections will remain
  * open and be reused, instead of reconnecting each
  * time a page loads.  However, it could cause problems
  * on a heavily loaded server with lots of connections
  * left open.
  */
 $mdb_conf['persist'] = TRUE;

 /*
  * Table prefix
  * In case you have an extra string appended
  * to the front of the table names to separate
  * them from other tables, put it here.
  * So, for example, setting the prefix to
  * "mdb_" would make the table names
  * "mdb_files", "mdb_", etc.
  * If you don't want a prefix just leave it empty.
  */
 $mdb_conf['prefix'] = "";

 /*
  * Adodb cache dir
  * Where adodb will cache its queries
  */
 $ADODB_CACHE_DIR = "cache";

 /*
  * Adodb cache timeout
  * Number of seconds a query will be cached for
  */
 $mdb_conf['secs2cache'] = 10;

 /*
  * Files/directories to exclude
  */
 $mdb_conf['excludes'] = array(
 	"non-anime",
	"incoming",
 );

 /*
  * File extensions to exclude
  */
 $mdb_conf['ext_excludes'] = array(
 	"sfv",
	"txt",
	"nfo",
 );

 /*
  * Title base
  * This is an array of base directories inside which are titles
  * So, for example, if you specify the array as "anime1" and "anime2",
  * then any directories inside these directories (non-recursively) will
  * be marked as titles.  So, for example, "anime1/ranma" and "anime1/evangelion"
  * will be titles, as will be "anime2/cowboy bebop" and "anime2/rozen maiden".
  * "anime2/cowboy bebop/manga" will not.
  */
 $mdb_conf['titlebase'] = array(
 	"anime",
 );

 /*
  * session_key
  * The key inside the session variable to use for session data
  */
 $mdb_conf['session_key'] = "mdb";

 /*
  * dbstats
  * Whether you want a database stats link to appear
  */
 $mdb_conf['dbstats'] = TRUE;

 /*
  * optimize
  * Whether you want the dbstats page to optimize tables
  */
 $mdb_conf['optimize'] = TRUE;

 /*
  * tagcloudmax
  * Maximum font size (in pixels) for tag cloud items
  */
 $mdb_conf['tagcloudmax'] = 50;

 /*
  * tagcloudmin
  * Minimum font size (in pixels) for tag cloud items
  */
 $mdb_conf['tagcloudmin'] = 10;

 /*
  * updatedbstatus_interval
  * How often (in milliseconds) to query updatedb status via AJAX
  */
 $mdb_conf['updatedbstatus_interval'] = 2000;

 /*
  * download
  * Whether to enable file downloading
  */
 $mdb_conf['download'] = TRUE;

 /*
  * download_log
  * Whether to log downloads
  */
 $mdb_conf['download_log'] = TRUE;

?>
