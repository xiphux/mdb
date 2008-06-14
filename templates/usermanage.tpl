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
   <td>Class</td>
   <td>Downloaded</td>
   <td>Delete</td>
 </tr>
 {foreach from=$users item=user}
 <tr class="{cycle values="odd,even"}">
   <td>{$user.username}</td>
   <td>
   {if $user.privilege > 0}
   Admin {if $user.id != $currentid}<a href="{$SCRIPT_NAME}?u=changeprivilege&uid={$user.id}&privilege=0"><span class="smalltext">[v]</span></a>{/if}
   {else}
   User {if $user.id != $currentid}<a href="{$SCRIPT_NAME}?u=changeprivilege&uid={$user.id}&privilege=1"><span class="smalltext">[^]</span></a>{/if}
   {/if}
   </td>
   <td title="{$user.size}">{$user.size|size}</td>
   <td class="textcenter">{if $user.id != $currentid}<a href="{$SCRIPT_NAME}?u=userdel&uid={$user.id}"><span class="smalltext warning">[X]</span></a>{/if}</td>
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
