<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$conf.company} - Live Support Solution (Powered By Help Center Live)</title>
<meta http-equiv="Content-Type" content="text/html; charset={$lang.charset}" />
<link href="{$conf.url}/templates/{$conf.template}/css/live.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div align="center">
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><img src="{$conf.url}/templates/{$conf.template}/images/live_top.gif" alt="" /></td>
  </tr>
</table>
<br /><br />
<form name="request_live_help" method="post" action="{$conf.url}/live/request.php?opchat">
{if $operatorid != ''}
<input name="operatorid" type="hidden" value="{$operatorid}" />
{else}
<table width="90%" border="0" cellspacing="1" cellpadding="2" class="border">
  <tr>
    <td class="dark"><div align="center">{if $retry == 'true'}<b>{$lang.retry_select_operator}</b>{else}{$lang.select_operator}{/if}</div></td>
  </tr>
    {section name=i loop=$avaliable}
        {if $avaliable[i].status == 'true'}
  <tr class="online">
    <td><div align="left"><input type="radio" name="operatorid" value="{$avaliable[i].id}" /> {$avaliable[i].name} - Online</div></td>
  </tr>
        {else}
  <tr class="offline">
    <td><div align="left"><input type="radio" name="operatorid" value="{$avaliable[i].id}" disabled="disabled" /> {$avaliable[i].name} - Offline</div></td>
  </tr>
        {/if}
    {/section}
</table>
{/if}
<br />
<br />
<table width="90%" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td><div align="center"><input type="submit" name="submit" value="{$lang.request_chat}" /></div></td>
  </tr>
</table>
</form>
</body>
</html>