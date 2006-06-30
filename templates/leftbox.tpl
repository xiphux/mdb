{*
 *  leftbox.tpl
 *  MDB: A media database
 *  Component: Left box template
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
 <div id="leftbox">
   <div id="loginbox">
     <h4>login</h4>
     <ul>
       {if $user}
         <li>logged in as: {$user.username}</li>
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
   <div id="functionbox">
     <h4>functions</h4>
     <ul>
       <li><a href="{$SCRIPT_NAME}?u=main">main</a></li>
       <li><a href="{$SCRIPT_NAME}?u=taglist">taglist</a></li>
       {if $user.privilege > 0}
         <li><a href="{$SCRIPT_NAME}?u=updatedb">updatedb</a></li>
         <li><a href="{$SCRIPT_NAME}?u=unmap">mappings</a></li>
       {/if}
       {if $dbstats}
         <li><a href="{$SCRIPT_NAME}?u=dbstats">dbstats</a></li>
       {/if}
     </ul>
   </div>
   <div id="searchbox">
     <h4>search</h4>
     <ul>
       <form action="{$SCRIPT_NAME}?u=search" method="post">
         <li>
           <input type="text" class="textfield" id="search" name="search" />
         </li>
         <li>
	   <select id="criteria" name="criteria">
	     <option value="All">All</option>
	     <option value="Titles">Titles</option>
	     <option value="Files">Files</option>
	   </select>
	 </li>
	 <li>
	   <input class="submit" type="submit" name="submit" value="Search" />
         </li>
       </form>
     </ul>
   </div>
 </div>
