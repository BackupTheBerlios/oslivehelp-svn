<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$conf.company} - Live Support Solution (Powered By Help Center Live)</title>
<meta http-equiv="Content-Type" content="text/html; charset={$lang.charset}" />
<link href="{$conf.url}/templates/{$conf.template}/css/admin.css" rel="stylesheet" type="text/css" />
{if $javascript != ""}
<script type="text/javascript" language="javascript" src="{$conf.url}/class/js/include.php?{$javascript}">
</script>
{/if}
</head>
<body>
<div id="wrapper">
<div id="header"><img src="{$conf.url}/templates/{$conf.template}/images/logo/main.gif" width="130" height="54" /></div>
<div id="page">
<h2 align="center">{$title}</h2>
{section name=i loop=$links_main}
<a href="{$links_main[i].url}">{$links_main[i].title}</a>
{/section}
<br /><br />
{include file=$content}
</div>
</div>
<div id="footer">
  {$lang.powered_by} <a href="http://www.helpcenterlive.com" target="_blank">Help Center Live</a> {$conf.version}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Copyright &copy; <a href="http://www.ubertec.co.uk">Help Center Live</a>. All Rights Reserved.</a>
</div>
</body>
</html>