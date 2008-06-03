{*
 * stats.tpl
 * CS161 project: Bookstore
 * Component: Database stats template
 *
 * Copyright (C) 2006 Christopher Han <cfh@gwu.edu>
 *}
<p><strong><span class="underline">{$appstring}</span></strong>:
<br />Copyright (c) {$cdate} {mailto address=$cauthor_email encode="hex" text=$cauthor} 
<br />Distributed under the terms of the <a href="http://www.gnu.org/licenses/gpl.html">GNU General Public License</a>, v2 or later
</p>
<p><strong><span class="underline">System statistics:</span></strong>
<br /><strong>Server: </strong>{$server}
<br /><strong>Uname: </strong>{$uname}
<br /><strong>Uptime (days): </strong>{$uptime_days}
<br /><strong>Uptime (%): </strong>{$uptime_percent}
<br /><strong>Load average: </strong>{$loadavg}
</p>
<p><strong><span class="underline">Data statistics:</span></strong>
<br /><strong>Files: </strong>{$files}
<br /><strong>Titles: </strong>{$titles}
<br /><strong>Users: </strong>{$users}
<br /><strong>Tags: </strong>{$tags}
<br /><strong>Downloads: </strong>{$downloads}
</p>
<p><strong><span class="underline">Database statistics:</span></strong></p>
