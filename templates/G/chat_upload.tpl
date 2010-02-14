<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$conf.company} - Live Support Solution (Powered By Help Center Live)</title>
<meta http-equiv="Content-Type" content="text/html; charset={$lang.charset}" />
<link href="{$conf.url}/templates/{$conf.template}/css/chat.css" rel="stylesheet" type="text/css" />
</head>
<body{$onload}>

<br /><br /><br />
{if $submit == 'true' && $fail == 'true'}
<div align="center"><b>{$lang.file_zero_bytes}</b></div><br />
{/if}
<form name="upload" enctype="multipart/form-data" method="post" action="{$conf.url}/live/chat/upload.php?chatid={$chatid}">
<table width="90%" align="center" border="0" cellspacing="1" cellpadding="2" class="menu">
  <tr>
    <td class="dark"><div align="center"><b>{$lang.send_file}</b></div></td>
    <td class="light"><div align="right"><input type="file" name="file" /></div></td>
  </tr>
  <tr>
    <td class="light" colspan="2"><div align="right">{$lang.max_file_size} {$max_size}&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="{$lang.transfer}" /></div></td>
  </tr>
</table></form>
<br /><br />

</body>
</html>