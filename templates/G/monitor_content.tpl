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
<body{$onload} id="content">
<br />
{section name=i loop=$chats}
<table width="90%" align="center" border="0" cellspacing="0" cellpadding="0" class="list">
  <tr class="alt">
    <td class="mini-button"><img src="{$conf.url}/templates/{$conf.template}/images/icons/popup_chatting_alt.gif" alt="" /></td>
    <td><div align="center">{$chats[i].title}</div></td>
    <td><div align="center">{$chats[i].name}</div></td>
    <td><div align="right">[<a href="javascript:parent.window.Monitor.accept('{$chats[i].id}', '{$chats[i].chatid}', '{$chats[i].type}');">{$lang.accept}</a>] [<a href="javascript:parent.window.Monitor.decline('{$chats[i].id}', '{$chats[i].chatid}', '{$chats[i].type}');">{$lang.decline}</a>]</div></td>
  </tr>
</table>
<br />
{/section}
<br />
{if $conf.monitor_traffic == true}
{section name=i loop=$visitors}
<table width="90%" align="center" border="0" cellspacing="0" cellpadding="0" class="list">
  <tr>
    <td class="mini-button" align="left" width="50"><a href="javascript:parent.window.Monitor.info('{$visitors[i].id}');"><img src="{$conf.url}/templates/{$conf.template}/images/buttons/mini_info.gif" alt="{$lang.more_info}" border="0" /></a>{if $visitors[i].chatting == 'true'}<img src="{$conf.url}/templates/{$conf.template}/images/icons/popup_chatting.gif" alt="{$visitors[i].status}" />{else}<a href="javascript:parent.window.Monitor.initiate('{$visitors[i].id}');"><img src="{$conf.url}/templates/{$conf.template}/images/icons/popup_browsing.gif" alt="{$visitors[i].status}" border="0" /></a>{/if}</td>
    <td align="left">{if $visitors[i].chatting != 'true'}<a href="javascript:parent.window.Monitor.initiate('{$visitors[i].id}');">{/if}<b>{$visitors[i].hostname}</b>{if $visitors[i].chatting != 'true'}</a>{/if}</td>
    <td align="right"><i>{$visitors[i].status}</i></td>
  </tr>
  <tr class="alt">
    <td colspan="3" align="center"><b>{$visitors[i].chat_department}</b><br/><a href="{$visitors[i].url}" target="_blank">{$visitors[i].page}</a></td>
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