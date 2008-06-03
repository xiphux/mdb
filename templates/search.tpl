{*
 *  search.tpl
 *  MDB: A media database
 *  Component: Search results template
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 *}
 <p>Search string: <span class="italic">{$search}</span></p>
 {if $results.titles}
 <p>Matching titles:
 {foreach from=$results.titles item=title}
 <br /><a href="{$SCRIPT_NAME}?u=title&id={$title.id}">{$title.title}</a>
 {/foreach}
 </p>
 {/if}
 {if $results.files}
 Matching files:<br />
 {include file='filelist.tpl' filelist=$results.files}
 {/if}
 {if !$results}
 <span class="italic">No matches!</span>
 {/if}
