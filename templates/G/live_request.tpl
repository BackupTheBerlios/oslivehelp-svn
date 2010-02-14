<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$conf.company} - Live Support Solution (Powered By Help Center Live)</title>
<meta http-equiv="Content-Type" content="text/html; charset={$lang.charset}" />
<link href="{$conf.url}/templates/{$conf.template}/css/live.css" rel="stylesheet" type="text/css" />
{if $javascript != ""}
<script type="text/javascript" language="javascript" src="{$conf.url}/class/js/include.php?{$javascript}">
</script>
{/if}
</head>
<body{$onload}>
<div align="center">
<table cellspacing="0" cellpadding="0" width="100%" class="bg">
  <tr>
    <td>
      <table width="100%" cellspacing="0" cellpadding="0" class="chat_bg">
        <tr>
          <td><img src="{$conf.url}/templates/{$conf.template}/images/logo/popup.jpg" alt="" /></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</div>
<div align="center" id="content">
<br /><br /><br /><br />
{$lang.wait_connect}
</div>
<div id="images"></div>
</body>
</html>