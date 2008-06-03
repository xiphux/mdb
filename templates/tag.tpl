{*
 *  tag.tpl
 *  MDB: A media database
 *  Component: Tag info template
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 *}
 <p>
 {if $tag}
 <span class="bold">{$tag.tag} ({$tag.count})</span>
 {if $user.privilege > 0}
 <br /><a href="{$SCRIPT_NAME}?u=deltag&id={$tag.id}"><span class="warning">Delete tag</span></a>
 {/if}
 </p>
 <p>Matching titles:
 {foreach from=$tag.titles item=title}
 <br /><a href="{$SCRIPT_NAME}?u=title&id={$title.id}">{$title.title}</a>
 {foreachelse}
 <span class="italic">No matching titles</span>
 {/foreach}
 {else}
 <span class="italic">No such tag!</span>
 {/if}
 </p>
