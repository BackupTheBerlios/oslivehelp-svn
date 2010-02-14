<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$conf.company} - Live Support Solution (Powered By Help Center Live)</title>
<meta http-equiv="Content-Type" content="text/html; charset={$lang.charset}" />
<link href="{$conf.url}/templates/{$conf.template}/css/admin.css" rel="stylesheet" type="text/css" />
<script language="Javascript" type="text/javascript" src="{$conf.url}/templates/{$conf.template}/js/admin.js"></script>
{if $javascript != ""}
<script type="text/javascript" language="javascript" src="{$conf.url}/class/js/include.php?{$javascript}">
</script>
{/if}
</head>
<body{$onload}>
<div id="wrapper">
<div id="header"><a href="{$conf.url}/admin/index.php"><img src="{$conf.url}/templates/{$conf.template}/images/logo/main.gif" width="130" height="54" /></a></div>
<div id="nav">
  <table border="0" cellpadding="0" cellspacing="0" class="nav">
    <tr>
      <td align="left">{section name=i loop=$links_bottom}{if $links_bottom[i].name != ''}<a href="{$links_bottom[i].url}" onMouseOver="swapImage('{$links_bottom[i].name}', 'on');" onMouseOut="swapImage('{$links_bottom[i].name}', 'off');"><img id="{$links_bottom[i].name}" src="{$conf.url}/templates/{$conf.template}/images/nav/{$links_bottom[i].name}_off.jpg" width="60" height="61" alt="{$links_bottom[i].title}" /></a>{/if}{/section}</td>
      <td align="right">{section name=i loop=$links_top}{if $links_top[i].name != ''}<a href="{$links_top[i].url}" onMouseOver="swapImage('{$links_top[i].name}', 'on');" onMouseOut="swapImage('{$links_top[i].name}', 'off');"><img id="{$links_top[i].name}" src="{$conf.url}/templates/{$conf.template}/images/nav/{$links_top[i].name}_off.jpg" width="60" height="61" alt="{$links_top[i].title}" /></a>{/if}{/section}</td>
    </tr>
  </table>
</div>
<div id="page">
<h2>{$title}</h2>
{section name=i loop=$links_main}
<a href="{$links_main[i].url}">{$links_main[i].title}</a>
{/section}
<br /><br />
{include file=$content}
</div>
</div>
<div id="footer">
  {$lang.powered_by} <a href="http://www.helpcenterlive.com" target="_blank">Help Center Live</a> {$conf.version}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Copyright &copy; <a href="http://www.ubertec.co.uk">Help Center Live</a>. All Rights Reserved.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="{$conf.url}/admin/update.php"><i>{$lang.updates}</i></a>
</div>
</body>
</html>