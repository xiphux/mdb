{*
 *  history.tpl
 *  MDB: A media database
 *  Component: Download history template
 *
 *  Copyright (C) 2007 Christopher Han <xiphux@gmail.com>
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
{if !$download_log}
<p>
<span class="warning">Note: Download logging is currently disabled!</span>
</p>
{/if}
<p>
<table>
  <tr>
    <td class="date"><span class="bold">Date</span></td>
    <td class="ip"><span class="bold">IP</span></td>
    <td class="filename"><span class="bold">File</span></td>
    <td class="filesize"><span class="bold">Size</span></td>
  </tr>
{foreach from=$userhistory item=dl}
  <tr class="{cycle values="odd,even"}">
    <td class="date">{$dl.time}</td>
    <td class="ip"><a href="http://ws.arin.net/cgi-bin/whois.pl?queryinput={$dl.ip}">{$dl.ip}</a></td>
    <td class="filename">
    {if $dl.fileinfo}
      <a href="{$SCRIPT_NAME}?u=file&id={$dl.fid}">{$dl.file}</a>
    {else}
      {$dl.file}
    {/if}
    </td>
    <td class="filesize" title="{$dl.fsize}">
      {$dl.fsize|size}
    </td>
  </tr>
{foreachelse}
  <tr><td></td><td></td><td><span class="italic">No files!</span></td><td></td></tr>
{/foreach}
</table>
</p>
{if $user.privilege > 0 && $otherhistory}
{foreach from=$otherhistory item=uhistory}
<p>
Downloads for {$uhistory.username}:
<table>
  <tr>
    <td class="date"><span class="bold">Date</span></td>
    <td class="ip"><span class="bold">IP</span></td>
    <td class="filename"><span class="bold">File</span></td>
    <td class="filesize"><span class="bold">Size</span></td>
  </tr>
  {foreach from=$uhistory.downloads item=dl}
  <tr class="{cycle values="odd,even"}">
    <td class="date">{$dl.time}</td>
    <td class="ip"><a href="http://ws.arin.net/cgi-bin/whois.pl?queryinput={$dl.ip}">{$dl.ip}</a></td>
    <td class="filename">
    {if $dl.fileinfo}
      <a href="{$SCRIPT_NAME}?u=file&id={$dl.fid}">{$dl.file}</a>
    {else}
      {$dl.file}
    {/if}
    </td>
    <td class="filesize" title="{$dl.fsize}">
      {$dl.fsize|size}
    </td>
  </tr>
  {foreachelse}
  <tr><td></td><td></td><td><span class="italic">No files!</span></td><td></td></tr>
  {/foreach}
</table>
</p>
{/foreach}
{/if}
