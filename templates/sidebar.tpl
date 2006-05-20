{*
 *  sidebar.tpl
 *  MDB: A media database
 *  Component: Sidebar template
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
 <div class="sidebar">
   <div class="block" id="loginblock">
     <div class="blocktitle" id="loginblocktitle">
       <div>&#187;login</div>
     </div>
     <div class="blockcontent" id="loginblockcontent">
       <ul>
         {if $user}
	   <li>{$user.username}</li>
	   <li><a href="{$SCRIPT_NAME}?u=logout">logout</a></li>
         {else}
           <form action="{$SCRIPT_NAME}?u=login" method="post">
             <li>
	       <label class="short" for="user">User</label>
               <input type="text" class="textfield" id="user" name="user" />
	     </li>
	     <li>
               <label class="short" for="pass">Pass</label>
               <input type="password" class="textfield" id="pass" name="pass" />
	     </li>
             <li>
	       <input class="submit" type="submit" name="submit" value="Login" />
	     </li>
           </form>
         {/if}
       </ul>
     </div>
   </div>
   <div class="block" id="functionblock">
     <div class="blocktitle" id="functionblocktitle">
       <div>&#187;functions</div>
     </div>
     <div class="blockcontent" id="functionblockcontent">
       <ul>
         <li><a href="{$SCRIPT_NAME}?u=search">search</a></li>
	 {if $user.admin}
	 <li><a href="{$SCRIPT_NAME}?u=updatedb">updatedb</a></li>
	 {/if}
       </ul>
     </div>
   </div>
   <div class="block" id="categoryblock">
     <div class="blocktitle" id="titleblocktitle">
       <div>&#187;titles</div>
     </div>
     <div class="blockcontent" id="titleblockcontent">
       <ul>
         {foreach from=$titlelist item=title key=letter}
	   <li>
	     <div class="miniblock" id="{$letter}block">
	       <div class="miniblocktitle" id="{$letter}blocktitle">
	         {$letter}
	       </div>
	       <div class="miniblockcontent" id="{$letter}blockcontent">
	         <ul>
		   {foreach from=$title item=t}
		     <li><a href="{$SCRIPT_NAME}?u=title&id={$t.id}">{$t.title}</a></li>
		   {/foreach}
		 </ul>
	       </div>
	     </div>
	   </li>
	 {foreachelse}
	   <li>No titles!</li>
	 {/foreach}
       </ul>
     </div>
   </div>
 </div>
