{*
 *  taglist.tpl
 *  MDB: A media database
 *  Component: Tag listing template
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
 <p>
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
 </p>
