{*
 *  updatedb.tpl
 *  MDB: A media database
 *  Component: Database update template
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Library General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 *}
 {literal}
 <script type="text/javascript">
 //<!CDATA[
 var req = null;
 function getXHR()
 {
   var requestobj = false;
   if (window.XMLHttpRequest)
     requestobj = new XMLHttpRequest();
   else if (window.ActiveXObject){ // if IE
     try { 
       requestobj = new ActiveXObject("Msxml2.XMLHTTP");
     } catch (e) {
       try { 
         requestobj = new ActiveXObject("Microsoft.XMLHTTP");              
       } catch (e){
         requestobj = false;
       }
     }
   }
   return requestobj;
 }
 var dots = 0;
 var timeout = null;
 var complete = false;
 function loadXHR()
 {
   if (req == null)
     req = getXHR();
   else if (req)
     req.abort();
   if (req) {
     req.open('GET','updatedbstatus.php',true);
     req.onreadystatechange = function() {
       if (req.readyState == 4 && req.status=="200") {
         if (req.responseText == "Complete") {
           complete = true;
           document.getElementById('updatedb').innerHTML='<span class="highlight">Database update complete!</span>';
         } else {
           text = 'Database updating<span class="warning">';
           for (i = 0; i < dots; i++)
             text = text + '.';
	   text = text + '</span>';
           document.getElementById('updatedb').innerHTML=text;
           if (dots >= 3)
             dots = 0;
           else
             dots++;
         }
       }
     }
   }
 }
 function updatestatus() {
   loadXHR();
   req.send(null);
   if (complete == false)
     timeout = window.setTimeout("updatestatus()",{/literal}{$interval}{literal});
   else
     clearTimeout(timeout);
 }
 timeout = window.setTimeout("updatestatus()",{/literal}{$interval}{literal});
 //]]>
 </script>
 {/literal}
 <div id="updatedb">
 Database updating
 </div>
