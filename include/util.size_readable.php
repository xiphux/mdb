<?php
/*
 * util.size_readable.php
 * MDB: A media database
 * Component: Util - size_readable
 * Return human readable sizes
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.1.0
 * @link        http://aidanlister.com/repos/v/function.size_readable.php
 * @param       int    $size        Size
 * @param       int    $unit        The maximum unit
 * @param       int    $retstring   The return string format
 * @param       int    $si          Whether to use SI prefixes
 *
 * (Slightly modified by Christopher Han)
 */
function size_readable($size, $unit = null, $retstring = null, $si = true)
{
    // Units
    if ($si === true) {
        $sizes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB', 'BB');
        $mod   = 1000;
    } else {
        $sizes = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB');
        $mod   = 1024;
    }
    $ii = count($sizes) - 1;
 
    // Max unit
    $unit = array_search((string) $unit, $sizes);
    if ($unit === null || $unit === false)
        $unit = $ii;
 
    // Return string
    if ($retstring === null)
       $retstring = '%01.2f %s';
 
    // Loop
    for ($i = 0; $unit != $i && $size >= 1024 && $i < $ii; $i++)
        $size /= $mod;
 
    return sprintf($retstring, $size, $sizes[$i]);
}

?>
