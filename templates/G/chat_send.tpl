<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$conf.company} - Live Support Solution (Powered By Help Center Live)</title>
<meta http-equiv="Content-Type" content="text/html; charset={$lang.charset}" />
<link href="{$conf.url}/templates/{$conf.template}/css/chat.css" rel="stylesheet" type="text/css" />
</head>
<body{$onload}>
<div align="center">
<table border="0" cellspacing="0" cellpadding="2" align="center">
{if $admin == 'true' && $_CHAT.isoperator == 'true' && $opchat == 'false'}
  <tr>
    <td>
      <div align="center">
        {$lang.insert}:
        <a href="javascript:parent.window.Chat.insert('nick');">[{$lang.guests_nick}]</a>
        <a href="javascript:parent.window.Chat.insert('push');">[{$lang.push}]</a>
        <a href="javascript:parent.window.Chat.insert('url');">[{$lang.url}]</a>
        <a href="javascript:parent.window.Chat.insert('image');">[{$lang.image}]</a>
        <a href="javascript:parent.window.Chat.insert('email');">[{$lang.email}]</a>
      </div>
    </td>
  </tr>
{/if}
  <tr>
    <td>
      <div align=\"center\">
        <form action="{$conf.url}/live/chat/send.php?chatid={$chatid}&amp;time={$epoch}{if $admin == 'true'}&amp;admin{/if}{if $opchat == 'true'}&amp;opchat{/if}" method="post" name="chat_form" id="chat_form" onsubmit="return parent.window.Chat.message();">
        <input type="text" autocomplete="off" name="chat_box" id="chat_box" size="30" maxlength="150" onkeypress="parent.window.Chat.typing();" />
        <input type="submit" name="chat_submit" id="chat_submit" value="{$lang.send}" /> <a href="{$conf.url}/live/chat/end.php?close{if $admin == 'true'}&admin{/if}&chatid={$chatid}" target="_top">[{$lang.end_chat}]</a>
{if $admin !== 'true' || $opchat == 'true'}
        <br /><br /><div align="center">Powered By <a href="http://www.helpcenterlive.com" target="_blank">Help Center Live</a> - &copy; <a href="http://www.ubertec.co.uk" target="_blank">Help Center Live</a>. All Rights Reserved.</div>
{/if}
        </form>
      </div>
    </td>
  </tr>
</table>
</div>
</body>
</html>