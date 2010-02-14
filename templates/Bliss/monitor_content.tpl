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
{section name=i loop=$chats}
<table width="90%" align="center" border="0" cellspacing="1" cellpadding="2" class="border">
  <tr>
    <td class="dark">{$chats[i].title}</td>
    <td class="medium">{$chats[i].name}</td>
    <td class="medium"><div align="right">[<a href="javascript:parent.window.Monitor.accept('{$chats[i].id}', '{$chats[i].chatid}', '{$chats[i].type}');">{$lang.accept}</a>] [<a href="javascript:parent.window.Monitor.decline('{$chats[i].id}', '{$chats[i].chatid}', '{$chats[i].type}');">{$lang.decline}</a>]</div></td>
  </tr>
</table>
<br />
{/section}
{if $conf.monitor_traffic == true}
{section name=i loop=$visitors}
<table width="90%" align="center" border="0" cellspacing="1" cellpadding="2" class="border">
  <tr>
    <td class="light"><i>{$lang.status}: {$visitors[i].status}</i></td>
  </tr>
  <tr>
    <td class="dark"><div align="center"><b>{$lang.hostname}</b></div></td>
  </tr>
  <tr>
    <td class="medium"><div align="center">{$visitors[i].hostname}</div></td>
  </tr>
  <tr>
    <td class="dark"><div align="center"><b>{$lang.current_page}</b></div></td>
  </tr>
  <tr>
    <td class="medium"><div align="center"><b>{$visitors[i].chat_department}</b></div></td>
  </tr>  
  <tr>
    <td class="dark"><div align="center"><a href="{$visitors[i].url}" target="_blank">{$visitors[i].page}</a></div></td>
  </tr>
  <tr>
    <td class="light"><div align="center">[<a href="javascript:parent.window.Monitor.info('{$visitors[i].id}');">{$lang.more_info}</a>] {if $visitors[i].chatting != 'true'}[<a href="javascript:parent.window.Monitor.initiate('{$visitors[i].id}');">{$lang.initiate}</a>]{/if}</div></td>
  </tr>
</table>
<br />
{/section}
{/if}
{section name=i loop=$sounds}
<embed src="{$conf.url}/templates/{$conf.template}/sounds/{$sounds[i].file}.mp3" autostart="true" hidden="true">
{if $sounds[i].text != ''}
<script type="text/javascript" language="javascript">
<!--
    Misc.alert_mac('{$sounds[i].text}');
//-->
</script>
{/if}
{/section}

</body>
</html>