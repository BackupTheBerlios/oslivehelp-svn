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
<table align="left" border="0" cellspacing="0" cellpadding="0" class="bg" width="100%">
  <tr>
    <td rowspan="3"><img src="{$conf.url}/templates/{$conf.template}/images/monitor.gif" alt="" /></td>
    <td><table border="0" cellspacing="0" cellpadding="2"><tr><td><b>{$lang.status}</b></td></tr></table></td>
    <td><table border="0" cellspacing="0" cellpadding="2"><tr><td><a href="javascript:parent.window.Monitor.change_status('on');"><span class="not_selected" id="status_on">{$lang.status_online}</span></a> <a href="javascript:parent.window.Monitor.change_status('off');"><span class="selected" id="status_off">{$lang.status_offline}</span></a></td></tr></table></td>
  </tr>
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="2"><tr><td><b>{$lang.sounds}</b></td></tr></table></td>
    <td><table border="0" cellspacing="0" cellpadding="2"><tr><td><a href="javascript:parent.window.Monitor.change_sounds('on');"><span class="selected" id="sounds_on">{$lang.status_on}</span></a> <a href="javascript:parent.window.Monitor.change_sounds('off');"><span class="not_selected" id="sounds_off">{$lang.status_off}</span></a></td></tr></table></td>
  </tr>
  <tr>
    <td colspan="2"><table border="0" cellspacing="0" cellpadding="2"><tr><td><div id="images"></div><b><a href="javascript:parent.window.Monitor.operator_chat();">[{$lang.start_opchat}]</a></b></td></tr></table></td>
  </tr>
</table>
</body>
</html>