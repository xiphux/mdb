{*
 *  filelistitem.tpl
 *  MDB: A media database
 *  Component: File listing item template
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 *}
{if $empty}
<tr><td colspan="2"><span class="italic">No files!</span></td></tr>
{else}
<tr class="{$class} {if $itemid}{$itemid}/{/if}">
<td class="filename" style="padding-left: {if $indentamt}{$indentamt*25}{else}0{/if}px" {if $subid && $dir}onClick="toggleVis('{$subid}')"{/if}>
{if $download && $user && !$dir}
<a href="{$SCRIPT_NAME}?u=file&id={$fileid}">{$filename}</a>
{else}
{if $subid}<span id="{$subid}">-</span> {/if}{$filename}
{/if}
</td>
{if $filesize}
<td class="filesize" title="{$filesize}">
{$filesize|size}
</td>
{else}
<td></td>
{/if}
</tr>
{/if}
