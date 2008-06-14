{*
 *  preferences.tpl
 *  MDB: A media database
 *  Component: Edit preferences template
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 *}
<div>
<form action="{$SCRIPT_NAME}?u=changetheme" method="post">
  <label class="short" for="theme">Set theme:</label>
  <select name="theme">
{foreach from=$themes item=theme}
  <option value="{$theme}" {if $theme == $selectedtheme}selected{/if}>{$theme}</option>
{/foreach}
  </select>
  <input type="submit" type="submit" name="submit" value="Change" />
</form>
</div>
<br />
<div>Change password:<br />
<form action="{$SCRIPT_NAME}?u=updatepass" method="post">
  <label class="short" for="oldpass">Old password:</label>
  <input type="password" class="textfield" id="oldpass" name="oldpass" />
<br />
  <label class="short" for="newpass">New password:</label>
  <input type="password" class="textfield" id="newpass" name="newpass" />
<br />
  <label class="short" for="newpass2">New password (again):</label>
  <input type="password" class="textfield" id="newpass2" name="newpass2" />
<br />
  <input type="submit" type="submit" name="submit" value="Change" />
</form>
</div>
