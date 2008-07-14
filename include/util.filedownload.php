<?php
/*
 *  util.filedownload.php
 *  MDB: A media database
 *  Component: Utility - filedownload
 *  Send a file to a user
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 */

function filedownload($id)
{
	global $mdb_conf, $db, $tables, $cache;

 	if (!(isset($_SESSION[$mdb_conf['session_key']]['user'])))
		return "You do not have permission to use this feature!";
	else if (!$mdb_conf['download'])
		return "File downloads have been disallowed";
 	else if (!isset($_GET['id']))
		return "No file specified";

	$file = DBGetRow("SELECT * FROM " . $tables['files'] . " WHERE id=" . $_GET['id']);
	if (!$file)
		return "No such file";

	if (ini_get('zlib.output_compression'))
		ini_set('zlib.output_compression', 'Off');
	header("Content-Description: " . basename($file['file']));
	if (function_exists("finfo_open")) {
		$mgc = finfo_open(FILEINFO_MIME);
		if ($mgc) {
			header("Content-Type: " . finfo_file($mgc,$mdb_conf['root'] . $file['file']));
			finfo_close($mgc);
		} else
			header("Content-Type: application/force-download");
	} else
		header("Content-Type: application/force-download");
	header("Content-Disposition: attachment; filename=\"" . basename($file['file']) . "\";");
	header("Content-Transfer-Encoding: binary");
	header("Expires: 0");
	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
	header("Pragma: public");
	header("Content-Length: " . @filesize($mdb_conf['root'] . $file['file']));
	set_time_limit(0);
	ob_clean();
	flush();
	if ($mdb_conf['download_log']) {
		$db->debug = FALSE;
		DBExecute("INSERT INTO " . $tables['downloads'] . " (ip,uid,user,fid,file,fsize) VALUES (" . DBqstr($_SERVER['REMOTE_ADDR']) . "," . $_SESSION[$mdb_conf['session_key']]['user']['id'] . "," . DBqstr($_SESSION[$mdb_conf['session_key']]['user']['username']) . "," . $file['id'] . "," . DBqstr($file['file']) . "," . $file['size'] . ")");
		if ($mdb_conf['debug'])
			$db->debug = TRUE;
		$cache->del("userhistory_" . $_SESSION[$mdb_conf['session_key']]['user']['id']);
		$cache->del("userhistorysize_" . $_SESSION[$mdb_conf['session_key']]['user']['id']);
		$cache->del("userlist");
		$cache->del("userhistory");
	}
	readfile($mdb_conf['root'] . $file['file']);
	return FALSE;
}

?>
