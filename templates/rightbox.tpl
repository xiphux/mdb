{*
 *  rightbox.tpl
 *  MDB: A media database
 *  Component: Right box template
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 *}
<div id="rightbox">
   <div class="block" id="categoryblock">
     <h4>titles</h4>
     <div class="blockcontent" id="titleblockcontent">
       <ul>
         {foreach from=$titlelist item=title key=letter}
	   <li>
	     <div class="miniblock" id="{$letter}block">
	       <div class="miniblocktitle" id="{$letter}blocktitle">
	         {$letter}
	       </div>
	       <div class="miniblockcontent" id="{$letter}blockcontent">
	         <ul>
		   {foreach from=$title item=t}
		     <li><a href="{$SCRIPT_NAME}?u=title&id={$t.id}">{$t.title}</a></li>
		   {/foreach}
		 </ul>
	       </div>
	     </div>
	   </li>
	 {foreachelse}
	   <li>No titles!</li>
	 {/foreach}
       </ul>
     </div>
   </div>
</div>
