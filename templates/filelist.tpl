{*
 *  filelist.tpl
 *  MDB: A media database
 *  Component: File listing template
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
<table>
<tr><td><span class="bold">File</span></td><td><span class="bold">Size</span></td></tr>
{foreach from=$filelist item=file}
<tr class="{cycle values="odd,even"}">
<td>
{if $user}
<a href="{$SCRIPT_NAME}?u=file&id={$file.id}">{$file.file}</a>
{else}
{$file.file}
{/if}
</td>
<td>
{$file.size|size}
</td>
</tr>
{foreachelse}
<tr><td colspan="2"><span class="italic">No files!</span></td></tr>
{/foreach}
</table>
