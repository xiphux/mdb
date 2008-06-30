{*
 *  dbcheck.tpl
 *  MDB: A media database
 *  Component: Database check template
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 *}
 <div class="dbcheck">Checking file -&gt; title mappings:<br />
 <ul>
 {if $unmap}
 <li><span class="warning">Warning: the following files do not have legitimate mappings!  Running updatedb is recommended.</span></li>
 {foreach from=$unmap item=i}
 <li><span class="warning">{$i.file}</span></li>
 {/foreach}
 {else}
 <li><span class="italic highlight">No unmapped files</span></li>
 {/if}
 </ul>
 </div>
 <div class="dbcheck">Checking file existence:<br />
 <ul>
 {if $exist}
 <li><span class="warning">Warning: the following files in the database do not exist on disk!  Running updatedb is recommended.</span></li>
 {foreach from=$exist item=j}
 <li><span class="warning">{$j.file}</span></li>
 {/foreach}
 {else}
 <li><span class="italic highlight">No nonexistent files</span></li>
 {/if}
 </ul>
 </div>
 {if $dbmutexcheck}
 <div class="dbcheck">Checking database update consistency:<br />
 <ul>
 <li>
 {if $dbmutexfixed}
 <span class="warning">Inconsistency detected, fixed</span>
 {else}
 <span class="highlight">Consistent</span>
 {/if}
 </li>
 </ul>
 </div>
 {/if}
 {if $optables}
 <div class="dbcheck">Optimizing tables:<br />
 <ul>
 {foreach from=$optables item=table}
 <li>Optimizing table <span class="highlight">{$table.Table}</span>... <span class="highlight">{$table.Msg_text}</span></li>
 {/foreach}
 </ul>
 </div>
 {/if}
