{*
 * stats.tpl
 * CS161 project: Bookstore
 * Component: Database stats template
 *
 * Copyright (C) 2006 Christopher Han <cfh@gwu.edu>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Library General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
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
</p>
<p><strong><span class="underline">Database statistics:</span></strong></p>
