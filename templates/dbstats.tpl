{*
 * dbstats.tpl
 * MDB: A media database
 * Component: Database stats template
 *
 * Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 *}
<p><strong><span class="underline">{$appstring}</span></strong>:
<br />Copyright (c) {$cdate} {mailto address=$cauthor_email encode="hex" text=$cauthor} 
<br />Distributed under the terms of the <a href="http://www.gnu.org/licenses/gpl.html">GNU General Public License</a>, v2 or later
</p>
<p><strong><span class="underline">System statistics:</span></strong>
<br /><strong>Server: </strong>{$server}
<br /><strong>Uname: </strong>{$uname}
{if $uptime_days}<br /><strong>Uptime (days): </strong>{$uptime_days}{/if}
{if $loadavg}<br /><strong>Load average: </strong>{$loadavg}{/if}
</p>
{if $memcached}
<p><strong><span class="underline">Memcached statistics:</span></strong>
{foreach from=$memcached key=type item=value}
<strong>{$type}: </strong>{$value}<br />
{/foreach}
</p>
{/if}
<p><strong><span class="underline">Data statistics:</span></strong>
<br /><strong>Files: </strong>{$files}
<br /><strong>Titles: </strong>{$titles}
<br /><strong>Users: </strong>{$users}
<br /><strong>Tags: </strong>{$tags}
<br /><strong>Links: </strong>{$links}
<br /><strong>Downloads: </strong>{$downloads}
<br /><strong>Updates: </strong>{$dbupdate}
<br /><strong>Preferences: </strong>{$preferences}
</p>
<p><strong><span class="underline">Database statistics:</span></strong></p>
{foreach from=$tablelist item=table}
<p>
<strong>Table: </strong>{$table.Name}<br />
{if $table.Engine}
<strong>Engine: </strong>{$table.Engine}<br />
{/if}
{if $table.Type}
<strong>Type: </strong>{$table.Type}<br />
{/if}
{if $table.Version}
<strong>Version: </strong>{$table.Version}<br />
{/if}
{if $table.Row_format}
<strong>Row format: </strong>{$table.Row_format}<br />
{/if}
{if $table.Rows}
<strong>Rows: </strong>{$table.Rows}<br />
{/if}
{if $table.Avg_row_length}
<strong>Average row length: </strong>{$table.Avg_row_length}<br />
{/if}
{if $table.Data_length}
<strong>Data length: </strong><span title="{$table.Data_length}">{$table.Data_length|size}</span><br />
{/if}
{if $table.Max_data_length}
<strong>Max data length: </strong><span title="{$table.Max_data_length}">{$table.Max_data_length|size}</span><br />
{/if}
{if $table.Index_length}
<strong>Index length: </strong><span title="{$table.Index_length}">{$table.Index_length|size}</span><br />
{/if}
{if $table.Data_free}
<strong>Data free: </strong>{$table.Data_free}<br />
{/if}
{if $table.Auto_increment}
<strong>Auto increment: </strong>{$table.Auto_increment}<br />
{/if}
{if $table.Create_time}
<strong>Create time: </strong>{$table.Create_time}<br />
{/if}
{if $table.Update_time}
<strong>Update time: </strong>{$table.Update_time}<br />
{/if}
{if $table.Check_time}
<strong>Check time: </strong>{$table.Check_time}<br />
{/if}
{if $table.Collation}
<strong>Collation: </strong>{$table.Collation}<br />
{/if}
{if $table.Checksum}
<strong>Checksum: </strong>{$table.Checksum}<br />
{/if}
{if $table.Create_options}
<strong>Create options: </strong>{$table.Create_options}<br />
{/if}
{if $table.Comment}
<strong>Comment: </strong>{$table.Comment}<br />
{/if}
{if $table.total_size}
<strong>Total size: </strong><span title="{$table.total_size}">{$table.total_size|size}</span><br />
{/if}
</p>
{/foreach}
<p><strong>Total database size: </strong><span title="{$dbsize}">{$dbsize|size}</span></p>
