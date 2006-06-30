{*
 *  header.tpl
 *  MDB: A media database
 *  Component: Page header template
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
 *}
{* $contentType = strpos($_SERVER['HTTP_ACCEPT'], 'application/xhtml+xml') === false ? 'text/html' : 'application/xhtml+xml';
header("Content-Type: $contentType; charset=utf-8"); *}
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<title>{$title}</title>
{literal}
<link rel="stylesheet" href="css/mdb.css" type="text/css" />
<script type="text/javascript" src="scripts/prototype.lite.js"></script>
<script type="text/javascript" src="scripts/moo.fx.js"></script>
<script type="text/javascript" src="scripts/moo.fx.pack.js"></script>
<script type="text/javascript">
//<!CDATA[
function highlightFormElements() {
    addFocusHandlers(document.getElementsByTagName("input"));
    addFocusHandlers(document.getElementsByTagName("textarea"));
}
function addFocusHandlers(elements) {
    for (i=0; i < elements.length; i++) {
        if (elements[i].type != "button" && elements[i].type != "submit" &&
            elements[i].type != "reset" && elements[i].type != "checkbox") {
            elements[i].onfocus=function() {this.className='focus';this.select()};
            elements[i].onclick=function() {this.select()};
            elements[i].onblur=function() {this.className=''};
        }
    }
}
function init_effects() {
	var minicontentblocks = document.getElementsByClassName('miniblockcontent');
	var minititleblocks = document.getElementsByClassName('miniblocktitle');
	var subAccordion = new fx.Accordion(
		minititleblocks,minicontentblocks,{opacity:true}
	);
}
window.onload = function() {highlightFormElements();init_effects();}
//]]>
</script>
{/literal}
{$smarty.capture.header}
</head>
<body>
<div id="bodydiv">
