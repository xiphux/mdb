{*
 *  title.tpl
 *  MDB: A media database
 *  Component: Title info template
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 *}
<p><span class="bold"><span class="underline">{$title.title}</span></span> [<span class="highlight" title="{$title.size}">{$title.size|size}</span>]</p>
{if $title.links}
<table>
{foreach from=$title.links name=links item=link}
<tr>
<td>{if $smarty.foreach.links.first}Info:{/if}</td>
<td><a href="{$link.url}">{$link.site}{if $link.name} ({$link.name}){/if}</a></td>
</tr>
{/foreach}
</table>
{/if}
Tags:
{foreach from=$title.tags name=tags item=tag}
{if !$smarty.foreach.tags.first},{/if} <a href="{$SCRIPT_NAME}?u=tag&id={$tag.id}">{$tag.tag}</a> {if $user}<a href="{$SCRIPT_NAME}?u=untag&tid={$title.id}&tag={$tag.id}"><span class="smalltext warning">[x]</span></a>{/if}
{foreachelse}
<span class="italic">None</span>
{/foreach}
{if $user}
{if $taglist}
<form action="{$SCRIPT_NAME}?u=addtag&tid={$title.id}" method="POST">
<label for="tag">Tag:</label> <select id="tag" name="tag">
{foreach from=$taglist item=tag}
<option value="{$tag.tag}">{$tag.tag}</option>
{/foreach}
</select> <input class="submit" type="submit" name="submit" value="Tag" />
</form>
{/if}
<form action="{$SCRIPT_NAME}?u=addtag&tid={$title.id}" method="POST">
<label for="tag">New tag:</label> <input type="text" id="tag" name="tag" /> <input class="submit" type="submit" name="submit" value="Add tag" />
</form>
{/if}
{include file='filelist.tpl' filelist=$title.files}
