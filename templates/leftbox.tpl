{*
 *  leftbox.tpl
 *  MDB: A media database
 *  Component: Left box template
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 *}
 <div id="leftbox">
   <div id="loginbox">
     <h4>login</h4>
     <ul>
       {if $user}
         <li>logged in as</li>
	 <li><span class="highlight">{$user.username}</span></li>
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
       {if $user}
         <li><a href="{$SCRIPT_NAME}?u=history">history</a></li>
	 <li><a href="{$SCRIPT_NAME}?u=preferences">preferences</a></li>
       {/if}
       {if $user.privilege > 0}
         <li><a href="{$SCRIPT_NAME}?u=usermanage">usermanage</a></li>
         <li><a href="{$SCRIPT_NAME}?u=updatedb">updatedb</a></li>
         <li><a href="{$SCRIPT_NAME}?u=dbcheck">dbcheck</a></li>
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
