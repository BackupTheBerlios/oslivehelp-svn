{if $onload == ''}
<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$conf.company} - Live Support Solution (Powered By Help Center Live)</title>
<meta http-equiv="Content-Type" content="text/html; charset={$lang.charset}" />
{if $javascript != ""}
<script type="text/javascript" language="javascript" src="{$conf.url}/class/js/include.php?{$javascript}&chatid={$chatid}{if $admin == 'true'}&admin{/if}">
</script>
{/if}
</head>
<frameset rows="{if $admin == 'true' && $opchat == 'false'}60,*,30,60,200,0{else}60,*,30,70,0{/if}" cols="*" framespacing="0" frameborder="0">
  <frame src="{$conf.url}/live/chat/top.php?chatid={$chatid}&amp;time={$epoch}{if $admin == 'true'}&amp;admin{/if}" name="chat_top" scrolling="no" noresize="noresize">
  <frame src="{$conf.url}/live/chat/display.php?chatid={$chatid}&amp;time={$epoch}{if $admin == 'true'}&amp;admin{/if}" name="chat_display" scrolling="yes">
  <frame src="{$conf.url}/live/chat/type.php?chatid={$chatid}&amp;time={$epoch}{if $admin == 'true'}&amp;admin{/if}" name="chat_type" scrolling="no" noresize="noresize">
  <frame src="{$conf.url}/live/chat/send.php?chatid={$chatid}&amp;time={$epoch}{if $admin == 'true'}&amp;admin{/if}{if $opchat == 'true'}&amp;opchat{/if}" name="chat_send" scrolling="no" noresize="noresize">
{if $admin == 'true' && $opchat == 'false'}
  <frame src="{$conf.url}/live/chat/admin.php?info&amp;chatid={$chatid}&amp;time={$epoch}{if $admin == 'true'}&amp;admin{/if}" name="chat_admin" scrolling="yes" noresize="noresize">
{/if}
  <frame src="{$conf.url}/live/chat/download.php?chatid={$chatid}&amp;time={$epoch}{if $admin == 'true'}&amp;admin{/if}" name="chat_download" scrolling="no" noresize="noresize">
</frameset>
</html>
{else}
<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$conf.company} - Live Support Solution (Powered By Help Center Live)</title>
<meta http-equiv="Content-Type" content="text/html; charset={$lang.charset}" />
<link href="{$conf.url}/templates/{$conf.template}/css/chat.css" rel="stylesheet" type="text/css" />
{if $javascript != ""}
<script type="text/javascript" language="javascript" src="{$conf.url}/class/js/include.php?{$javascript}&chatid={$chatid}{if $admin == 'true'}&amp;admin{/if}">
</script>
{/if}
</head>
<body{$onload}>
{$text}
</body>
</html>
{/if}