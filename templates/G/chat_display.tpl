<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$conf.company} - Live Support Solution (Powered By Help Center Live)</title>
<meta http-equiv="Content-Type" content="text/html; charset={$lang.charset}" />
<link href="{$conf.url}/templates/{$conf.template}/css/chat.css" rel="stylesheet" type="text/css" />
</head>
<body{$onload} id="content">

<p align="center">
<i><span class="operator">{$now_chatting}</span></i>
</p>

{section name=i loop=$messages}
{if $messages[i].x == 'o'}
<span class="operator"><b>{$messages[i].name}:</b> {$messages[i].message}</span><br />
{else}
<span class="guest"><b>{$messages[i].name}:</b> {$messages[i].message}</span><br />
{/if}
{/section}

</body>
</html>