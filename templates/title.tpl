{*
 *  title.tpl
 *  MDB: A media database
 *  Component: Title info template
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
<p><span class="bold"><span class="underline">{$title.title}</span></span> [<span class="highlight" title="{$title.size}">{$title.size|size}</span>]</p>
{if $title.info}
<table>
{foreach from=$title.info.animenfo name=nfo item=nfolnk}
<tr>
<td>{if $smarty.foreach.nfo.first}Info:{/if}</td>
<td><a href="{$nfolnk.link}">AnimeNFO{if $nfolnk.name} ({$nfolnk.name}){/if}</a></td>
</tr>
{/foreach}
</table>
{/if}
<p>
Tags:
{foreach from=$title.tags name=tags item=tag}
{if !$smarty.foreach.tags.first},{/if} <a href="{$SCRIPT_NAME}?u=tag&id={$tag.id}">{$tag.tag}</a> {if $user}<a href="{$SCRIPT_NAME}?u=untag&tid={$title.id}&tag={$tag.id}">[-]</a>{/if}
{foreachelse}
<span class="italic">None</span>
{/foreach}
</p>
<br />
{if $user}
<p>
{if $taglist}
<form action="{$SCRIPT_NAME}?u=addtag&tid={$title.id}" method="POST">
<label for="tag">Tag:</label> <select id="tag" name="tag">
{foreach from=$taglist item=tag}
<option value="{$tag.tag}">{$tag.tag}</option>
{/foreach}
</select> <input class="submit" type="submit" name="submit" value="Tag" />
</form>
<br />
{/if}
<form action="{$SCRIPT_NAME}?u=addtag&tid={$title.id}" method="POST">
<label for="tag">New tag:</label> <input type="text" id="tag" name="tag" /> <input class="submit" type="submit" name="submit" value="Add tag" />
</form>
</p>
{/if}
{include file='filelist.tpl' filelist=$title.files}
