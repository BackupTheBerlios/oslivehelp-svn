<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$conf.company} - Live Support Solution (Powered By Help Center Live)</title>
<meta http-equiv="Content-Type" content="text/html; charset={$lang.charset}" />
<link href="{$conf.url}/templates/{$conf.template}/css/chat.css" rel="stylesheet" type="text/css" />
</head>
<body{$onload}>
<form name="timer">
<table width="100%" height="60" align="center" border="0" cellspacing="0" cellpadding="5" class="top_bg">
  <tr>
    <td valign="top" class="{if $picture !== '1'}no_{/if}picture">
      <div align="right">
        <table align="right" border="0" cellspacing="0" cellpadding="0" style="padding-right: 5px;">
          <tr>
            <td valign="top">
              {$lang.sounds} : <a href="javascript:parent.window.Chat.sounds('on');"><span class="selected" id="sounds_on">{$lang.status_on}</span></a> <a href="javascript:parent.window.Chat.sounds('off');"><span class="not_selected" id="sounds_off">{$lang.status_off}</span></a><br />
              {$lang.focus_win} : <a href="javascript:parent.window.Chat.focus('on');"><span class="selected" id="focus_on">{$lang.status_on}</span></a> <a href="javascript:parent.window.Chat.focus('off');"><span class="not_selected" id="focus_off">{$lang.status_off}</span></a><br />
              {$lang.text_size} : <a href="javascript:parent.window.Chat.font_larger();">[A+]</a> <a href="javascript:parent.window.Chat.font_smaller();">[A-]</a><br>
              {$lang.chat_time} : <span id="time">00:00</span>
            </td>
{if $showpic == '1'}
            <td valign="top">
              <img src="{$conf.url}/live/icon.php?picture&id={$_CHAT.operatorid}" alt="{$_CHAT.operator}" height="50" />
            </td>
{/if}
          </tr>
        </table>
      </div>
    </td>
  </tr>
</table>
</form>
<div id="images"></div>
</body>
</html>