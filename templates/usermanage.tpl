{*
 *  usermanage.tpl
 *  MDB: A media database
 *  Component: User Manage template
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 *}
 <div>
 <table>
 <tr>
   <td>Username</td>
   <td>Privilege</td>
   <td>Change</td>
   <td>Downloaded</td>
   <td>Delete</td>
 </tr>
 {foreach from=$users item=user}
 <tr class="{cycle values="odd,even"}">
   <td>{$user.username}</td>
   <td>{if $user.privilege > 0}Admin{else}User{/if}</td>
   <td class="textcenter">
   {if $user.id != $currentid}
   <form class="inline" action="{$SCRIPT_NAME}?u=changeprivilege" method="post">
   <input type="hidden" name="uid" value="{$user.id}" />
   {if $user.privilege > 0}
   <input type="hidden" name="privilege" value="0" />
   <input type="submit" name="submit" value="v" />
   {else}
   <input type="hidden" name="privilege" value="1" />
   <input type="submit" name="submit" value="^" />
   {/if}
   </form>
   {/if}
   </td>
   <td title="{$user.size}">{$user.size|size}</td>
   <td class="textcenter">
   {if $user.id != $currentid}
   <form class="inline" action="{$SCRIPT_NAME}?u=userdel" method="post">
   <input type="hidden" name="uid" value="{$user.id}" />
   <input class="warning" type="submit" name="submit" value="x" />
   </form>
   {/if}
   </td>
 </tr>
 {foreachelse}
 <tr>
   <td colspan="3"><span class="italic">No users</span></td>
 </tr>
 {/foreach}
 </table>
 </div>
 <br />
 <div>
 <form action="{$SCRIPT_NAME}?u=useradd" method="post">
 Add user:
 <br />
   <label class="short" for="user">User</label>
   <input type="text" class="textfield" id="user" name="user" />
 <br />
   <label class="short" for="pass">Pass</label>
   <input type="password" class="textfield" id="pass" name="pass" />
 <br />
   <label class="short" for="admin">Admin</label>
   <input type="checkbox" id="admin" name="admin" />
 <br />
   <input type="submit" type="submit" name="submit" value="Create" />
 </form>
 </div>
