<?php	
  require('includes/application_top.php');
 ?>
<html>
<head>
<title>Access Denied</title>
<style>
.swbig {font-size: 16px; font-weight: bolder; font-family: Arial, Verdana; color:red;}
td {font-size: 12px; font-family: Arial, Verdana}
</style>
</head>
<body bgcolor=#FFFFFF text=#000000 link=#003399>

<table cellpadding=0 cellspacing=0 width=100% height=100% border=0 >
<tr><td align=center>

<table cellpadding=0 cellspacing=0 border=0 width=600 align=center>
<tr>

<td valign=top>
<img src="1.gif" width=1 height=40><br>
<table cellpadding=0 cellspacing=0 border=0 bgcolor=#E3E3E3 height=100% align="center"> 
<tr>
	<td valign=top rowspan=2><img src="images/web_site_lt.gif" width=20 height=22></td>
	<td><img src="images/1.gif" width=1 height=22></td>
	<td valign=top rowspan=2><img src="images/web_site_rt.gif" width=20 height=22></td>
</tr>
<tr><td valign=top>
<span class=SWbig>Access Denied.</span>
<br>
<br>
Please contact your systems Adminstrator.
<br>
<br>
<a href="<?php echo tep_href_link(FILENAME_DEFAULT);?>" >go back--></a>
</td>
</tr>
<tr>
	<td><img src="images/web_site_lb.gif" width=20 height=22></td>
	<td><img src="images/1.gif" width=1 height=22></td>
	<td><img src="images/web_site_rb.gif" width=20 height=22></td>
</tr>
</table><br>

</td></tr></table>

</td></tr></table>
</body>
</html>
