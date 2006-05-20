<?php
/*
 *  mdb.lib.php
 *  MDB: A media database
 *  Component: Function library
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

 function titlelist()
 {
 	global $db,$tables;
	$letters = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
	$numbers = array("0","1","2","3","4","5","6","7","8","9");
	foreach ($numbers as $i => $number) {
		$temp = $db->GetArray("SELECT * FROM " . $tables['titles'] . " WHERE LEFT(TRIM(title),1)=" . $db->qstr($number) . " ORDER BY title");
		if (sizeof($temp) > 0)
			$titles[$number] = $temp;
	}
	foreach ($letters as $i => $letter) {
		$temp = $db->GetArray("SELECT * FROM " . $tables['titles'] . " WHERE LEFT(UPPER(TRIM(title)),1)=" . $db->qstr($letter) . " ORDER BY title");
		if (sizeof($temp) > 0)
			$titles[strtolower($letter)] = $temp;
	}
	$temp = $db->GetArray("SELECT * FROM " . $tables['titles'] . " WHERE LEFT(UPPER(TRIM(title)),1) NOT IN ('" . implode("','",$letters) . "','" . implode("','",$numbers) . "') ORDER BY title");
	if (sizeof($temp) > 0)
		$titles['other'] = $temp;
	return $titles;
 }

?>
