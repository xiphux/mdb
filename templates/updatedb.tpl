{*
 *  updatedb.tpl
 *  MDB: A media database
 *  Component: Database update template
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
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
     req.open('GET','include/updatedbstatus.php',true);
     req.onreadystatechange = function() {
       if (req.readyState == 4 && req.status=="200") {
         if (req.responseText == "Database updating") {
           text = 'Database updating<span class="warning">';
           for (i = 0; i < dots; i++)
             text = text + '.';
	   text = text + '</span>';
           document.getElementById('updatedb').innerHTML=text;
           if (dots >= 3)
             dots = 0;
           else
             dots++;
         } else {
           complete = true;
           document.getElementById('updatedb').innerHTML='<span class="highlight">' + req.responseText + '</span>';
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
