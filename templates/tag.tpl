{*
 *  tag.tpl
 *  MDB: A media database
 *  Component: Tag info template
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
