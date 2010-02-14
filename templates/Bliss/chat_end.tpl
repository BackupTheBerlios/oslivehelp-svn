<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$conf.company} - Live Support Solution (Powered By Help Center Live)</title>
<meta http-equiv="Content-Type" content="text/html; charset={$lang.charset}" />
<link href="{$conf.url}/templates/{$conf.template}/css/chat.css" rel="stylesheet" type="text/css" />
{literal}
<script language="Javascript" type="text/javascript">
<!--
function rate(rating)
{
    for (var i = 1; i < rating+1; i++) {
        document.getElementById(i).checked = 'checked';
    }
    for (var i = rating+1; i < 6; i++) {
        document.getElementById(i).checked = '';
    }
}
//-->
</script>
{/literal}
</head>
<body{$onload}>
{if $visitor == 'true'}
<div align="center">
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><img src="{$conf.url}/templates/{$conf.template}/images/live_top.gif" alt="" /></td>
  </tr>
</table>
<br /><br />
</div>
<div align="center">
{if $display == 'email'}
<table cellspacing="0" cellpadding="0" width="80%">
  <tr>
    <td align="center"><b>{$lang.sent_transcript}</b></td>
  </tr>
</table>
<br />
{/if}
{if $display == 'review'}
<table cellspacing="0" cellpadding="0" width="80%">
  <tr>
    <td align="center">{$lang.review_sent}</td>
  </tr>
</table>
<br />
{/if}
<div align="center">
{if $display == 'default'}
{$end_message}<br />
{/if}
{if $display == 'print'}
<table cellspacing="10" cellpadding="0" class="large">
  <tr>
    <td>{$lang.nick}: </td>
    <td>{$nick}</td>
  </tr>
  <tr>
    <td>{$lang.operator}: </td>
    <td>{$operator}</td>
  </tr>
  <tr>
    <td>{$lang.department}: </td>
    <td>{$department}</td>
  </tr>
  <tr>
    <td>{$lang.email}: </td>
    <td>{$email}</td>
  </tr>
  <tr>
    <td>{$lang.time}: </td>
    <td>{$time}</td>
  </tr>
</table>
<br />
<table cellspacing="10" cellpadding="0" class="large">
  <tr>
    <td>
      {$chat}
    </td>
  </tr>
</table>
{else}
<br />
<br />
<form action="{$conf.url}/live/chat/end.php?chatid={$chatid}" method="post">
<table cellspacing="0" cellpadding="0" width="80%" class="border">
  <tr>
    <td align="center" class="light" style="padding: 5px;">{$lang.email_transcript}</td>
  </tr>
  <tr>
    <td style="padding: 10px;" align="center">{$lang.email}: <input type="text" name="email" /> <input type="submit" value="{$lang.submit}" name="submit" /></td>
  </tr>
</table>
</form>
<br />
<br />
<a href="{$conf.url}/live/chat/end.php?print&chatid={$chatid}">{$lang.print_transcript}</a>
<br />
<br />
<br />
<form action="{$conf.url}/live/chat/end.php?chatid={$chatid}" method="post">
<table cellspacing="0" cellpadding="0" width="80%" class="border">
  <tr>
    <td  class="light" align="center" style="padding: 5px;">{$lang.write_review}</td>
  </tr>
  <tr>
    <td style="padding: 10px;" align="center">{$lang.rating}:</td>
  </tr>
  <tr>
    <td style="padding: 10px;" align="center">{$lang.min} - <input type="radio" name="1" vaue="1" id="1" onclick="rate(1);" /> <input type="radio" name="2" vaue="2" id="2" onclick="rate(2);" /> <input type="radio" name="3" vaue="3" id="3" onclick="rate(3);" /> <input type="radio" name="4" vaue="4" id="4" onclick="rate(4);" /> <input type="radio" name="5" vaue="5" id="5" onclick="rate(5);" /> - {$lang.max}</td>
  </tr>
  <tr>
    <td style="padding: 10px;" align="center">{$lang.review}:</td>
  </tr>
  <tr>
    <td style="padding: 10px;" align="center"><textarea name="message" cols="30" rows="6"></textarea></td>
  </tr>
</table>
<br />
<input type="submit" value="{$lang.send}" name="review" />
</form>
{/if}
</div>
{/if}
</body>
</html>