{*
 *  history.tpl
 *  MDB: A media database
 *  Component: Download history template
 *
 *  Copyright (C) 2007 Christopher Han <xiphux@gmail.com>
 *}
{if !$download_log}
<p>
<span class="warning">Note: Download logging is currently disabled!</span>
</p>
{/if}
<p>Total: [<span class="highlight" title="{$userhistorysize}">{$userhistorysize|size}</span>]
<table class="dltable">
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
    {if $dl.file_exists}
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
Downloads for {$uhistory.username} [<span class="highlight" title="{$uhistory.total}">{$uhistory.total|size}</span>]:
<table class="dltable">
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
    {if $dl.file_exists}
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
