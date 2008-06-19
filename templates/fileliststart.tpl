{*
 *  fileliststart.tpl
 *  MDB: A media database
 *  Component: File listing head template
 *
 *  Copyright (C) 2006 Christopher Han <xiphux@gmail.com>
 *}
{literal}
<script type="text/javascript">
//<!CDATA[
function toggleVis(obj)
{
	var elements = [];
	var all = document.getElementsByTagName('tr');
	$A(all).each(function(el){
		if (new RegExp(obj).test(el.className)) elements.push(el);
	});
	if (document.getElementById(obj).innerHTML=="-") {
		document.getElementById(obj).innerHTML="+";
		for (var i = 0; i < elements.length; i++) {
			$(elements[i]).tween('display','none');
		}
	} else {
		document.getElementById(obj).innerHTML="-";
		for (var i = 0; i < elements.length; i++) {
			$(elements[i]).tween('display','');
		}
	}
}
//]]>
</script>
{/literal}
<table class="filelist">
<tr><td class="filename"><span class="bold">File</span></td><td class="filesize"><span class="bold">Size</span></td></tr>
