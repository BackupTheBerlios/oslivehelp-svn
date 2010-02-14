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
<table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" class="bg">
  <tr>
    <td>
      <table align="left" border="0" cellspacing="0" cellpadding="0" class="status_bg">
        <tr>
          <td width="280"><img src="{$conf.url}/templates/{$conf.template}/images/logo/popup.jpg" alt="" /><div id="images"></div></td>
          <td><a href="javascript:parent.window.Monitor.change_status('img', '{$conf.url}/templates/{$conf.template}/images/nav/popup_live_on.jpg', '{$conf.url}/templates/{$conf.template}/images/nav/popup_live_off.jpg');"><img src="{$conf.url}/templates/{$conf.template}/images/nav/popup_live_on.jpg" id="status" alt="{$lang.status}" border="0" /></a><span class="selected" id="status_on"></span><span class="not_selected" id="status_off"></span></td>
          <td><a href="javascript:parent.window.Monitor.change_sounds('img', '{$conf.url}/templates/{$conf.template}/images/nav/popup_sounds_on.jpg', '{$conf.url}/templates/{$conf.template}/images/nav/popup_sounds_off.jpg');"><img src="{$conf.url}/templates/{$conf.template}/images/nav/popup_sounds_on.jpg" id="sounds" alt="{$lang.sounds}" border="0" /></a><span class="selected" id="sounds_on"></span><span class="not_selected" id="sounds_off"></span></td>
          <td><a href="javascript:parent.window.Monitor.operator_chat();"><img src="{$conf.url}/templates/{$conf.template}/images/nav/popup_operator_on.jpg" alt="{$lang.opchat}" border="0" /></a></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>