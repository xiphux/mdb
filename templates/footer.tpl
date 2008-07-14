{*
 *  footer.tpl
 *  MDB: A media database
 *  Component: Page footer template
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 *}
 </div>
 <div id="footer">
 {$banner}<br />
 Titles: {$titlecount} | Files: {$filecount} | Size: <span title="{$size}">{$size|size}</span><br />
 Last update: {if $update}{$update|date_format:"%D %T"}{else}Never{/if}
 {if $updating}<br /><span class="warning">Database currently updating</span>{/if}
 <br />Queries executed: {$queries}
 {if $cache}
 <br />Cache type: {$cachetype}
 <br />Cache hits: {$cachehits} | Cache misses: {$cachemisses}
 {/if}
 <br />Execution time: {$exectime} sec
 </div>
 </body>
 </html>
