<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$conf.company} - Live Support Solution (Powered By Help Center Live)</title>
<meta http-equiv="Content-Type" content="text/html; charset={$lang.charset}" />
<link href="{$conf.url}/templates/{$conf.template}/css/monitor.css" rel="stylesheet" type="text/css" />
{if $javascript != ""}
<script type="text/javascript" language="javascript" src="{$conf.url}/class/js/include.php?{$javascript}">
</script>
{/if}
</head>
<body{$onload}>
<br />
<form action="{$conf.url}/admin/monitor/initiate.php?chatid={$chatid}" method="post">
<table width="90%" align="center" border="0" cellspacing="1" cellpadding="2" class="border">
  <tr>
    <td class="dark" colspan="2"><div align="center"><b>{$lang.hostname}</b></div></td>
  </tr>
  <tr>
    <td class="medium" colspan="2"><div align="center">{$info.hostname}</div></td>
  </tr>
  <tr>
    <td class="dark" colspan="2"><div align="center"><b>{$lang.ip_addr}</b></div></td>
  </tr>
  <tr>
    <td class="medium" colspan="2"><div align="center">{$info.ip}</div></td>
  </tr>
  <tr>
    <td class="light" colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td class="dark" colspan="2"><div align="center"><b>{$lang.department}</b></div></td>
  </tr>
  <tr>
    <td class="medium" colspan="2">
      <div align="center">
      <select name="department">
{section name=i loop=$departments}
        <option value="{$departments[i].id}">{$departments[i].name}</option>
{/section}
      </select>
      </div>
    </td>
  </tr>
  <tr>
    <td class="light" colspan="3"><div align="center"><input type="submit" name="initiate" value="{$lang.initiate}" /></div></td>
  </tr>
</table>
<br /><br />
</form>
</body>
</html>