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
 <li>{$i.file}</li>
 {/foreach}
 {else}
 <li><span class="italic">No unmapped files</span></li>
 {/if}
 </ul>
 </div>
 {if $optables}
 <div class="dbcheck">Optimizing tables:<br />
 <ul>
 {foreach from=$optables key=table item=status}
 <li>Optimizing table {$table}... {if $status}ok{else}failed{/if}</li>
 {/foreach}
 </ul>
 </div>
 {/if}
