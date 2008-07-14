{*
 *  database.tpl
 *  MDB: A media database
 *  Component: Database operations template
 *
 *  Copyright (C) 2008 Christopher Han <xiphux@gmail.com>
 *}
<form action="{$SCRIPT_NAME}?u=updatedb" method="post">
<label class="short" for="updatedb">Update file database:</label>
<input type="submit" name="updatedb" value="updatedb" />
</form>
<form action="{$SCRIPT_NAME}?u=dbcheck" method="post">
<label class="short" for="dbcheck">Check database status (may take awhile):</label>
<input type="submit" name="dbcheck" value="dbcheck" />
</form>
<form action="{$SCRIPT_NAME}?u=dbstats" method="post">
<label class="short" for="dbstats">Query database statistics:</label>
<input type="submit" name="dbstats" value="dbstats" />
</form>
{if $cache}
<form action="{$SCRIPT_NAME}?u=cacheflush" method="post">
<label class="short" for="cacheflush">Flush cache:</label>
<input type="submit" name="cacheflush" value="cacheflush" />
</form>
{/if}
