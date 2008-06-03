{*
 *  taglist.tpl
 *  MDB: A media database
 *  Component: Tag listing template
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 *}
 <div id="tagbox">
 {if $taglist}
 {assign var="max" value=0}
 {foreach from=$taglist item=tag}
 {if $tag.count > $max}
 {assign var="max" value=$tag.count}
 {/if}
 {/foreach}
 {foreach from=$taglist item=tag}
 <span style="font-size:{$tag.count*$tagcloudrange/$max+$tagcloudmin}px;"><a href="{$SCRIPT_NAME}?u=tag&id={$tag.id}">{$tag.tag}</a>({$tag.count}) <span>
 {/foreach}
 {else}
 <span class="italic">No tags</span>
 {/if}
 </div>
