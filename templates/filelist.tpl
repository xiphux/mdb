{*
 *  filelist.tpl
 *  MDB: A media database
 *  Component: File listing template
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 *}
<table class="filelist">
<tr><td class="filename"><span class="bold">File</span></td><td class="filesize"><span class="bold">Size</span></td></tr>
{foreach from=$filelist item=file}
<tr class="{cycle values="odd,even"}">
<td class="filename">
{if $download && $user}
<a href="{$SCRIPT_NAME}?u=file&id={$file.id}">{$file.file}</a>
{else}
{$file.file}
{/if}
</td>
<td class="filesize" title="{$file.size}">
{$file.size|size}
</td>
</tr>
{foreachelse}
<tr><td colspan="2"><span class="italic">No files!</span></td></tr>
{/foreach}
</table>
