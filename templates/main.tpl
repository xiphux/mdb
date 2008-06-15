{*
 *  main.tpl
 *  MDB: A media database
 *  Component: Main page template
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 *}
 <p>{$banner}</p>
 <p><span class="bold">Titles:</span> {$titlecount}<br /><span class="bold">Files:</span> {$filecount}<br /><span class="bold">Total size of all files in database:</span> <span title="{$size}">{$size|size}</span><br /><span class="bold">Last database update:</span> {if $update}{$update|date_format:"%B %e, %Y %T"}{else}Never{/if}<br />{if $updating}<span class="bold warning">Database currently updating</span>{/if}</p>
