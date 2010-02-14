<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$conf.company} - Live Support Solution (Powered By Help Center Live)</title>
<meta http-equiv="Content-Type" content="text/html; charset={$lang.charset}" />
<link href="{$conf.url}/templates/{$conf.template}/css/chat.css" rel="stylesheet" type="text/css" />
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
</div>
<table cellspacing="10" cellpadding="0" class="large">
  <tr>
    <td>{$lang.nick}: </td>
    <td>{$details.guest}</td>
  </tr>
  <tr>
    <td>{$lang.operator}: </td>
    <td>{$details.operator}</td>
  </tr>
  <tr>
    <td>{$lang.department}: </td>
    <td>{$details.department}</td>
  </tr>
  <tr>
    <td>{$lang.email}: </td>
    <td>{$details.email}</td>
  </tr>
  <tr>
    <td>{$lang.time}: </td>
    <td>{$details.time}</td>
  </tr>
</table>
<br />
<table cellspacing="10" cellpadding="0" class="large">
  <tr>
    <td>
      {$details.transcript}
    </td>
  </tr>
</table>
</body>
</html>