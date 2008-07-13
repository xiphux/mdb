{*
 *  title.tpl
 *  MDB: A media database
 *  Component: Title info template
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 *}
{literal}
<script type="text/javascript">
//<!CDATA[
document.openAccordion = function() {
	if (document.titlelistAccordion)
		document.titlelistAccordion.display(document.getElementById("{/literal}{$letter}{literal}blockcontent"));
}
//]]>
</script>
{/literal}
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
<table>
{foreach from=$title.tags name=tags item=tag}
<tr>
<td>{if $smarty.foreach.tags.first}Tags:{/if}</td>
<td><a href="{$SCRIPT_NAME}?u=tag&id={$tag.id}">{$tag.tag}</a></td>
{if $user}<td><form class="inline" action="{$SCRIPT_NAME}?u=untag" method="post"><input type="hidden" name="tid" value="{$title.id}" /><input type="hidden" name="tag" value="{$tag.id}" /><input class="warning" type="submit" name="submit" value="x" /></form></td>{/if}
</tr>
{foreachelse}
<tr>
<td>Tags:</td>
<td><span class="italic">None</span></td>
</tr>
{/foreach}
</table>
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
