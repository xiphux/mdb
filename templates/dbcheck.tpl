{*
 *  dbcheck.tpl
 *  MDB: A media database
 *  Component: Database check template
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 *}
 {if $unmap}
 <p><span class="warning">Warning: the following files do not have legitimate file -&gt; title mappings!  Running updatedb is recommended.</span></p>
 <p>
 {foreach from=$unmap item=i}
 {$i.file}<br />
 {/foreach}
 </p>
 {else}
 <span class="italic">No unmapped files</span>
 {/if}
