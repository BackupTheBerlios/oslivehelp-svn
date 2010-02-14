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
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><img src="{$conf.url}/templates/{$conf.template}/images/live_top.gif" alt="" /></td>
  </tr>
</table>
<br /><br />

{if $divert == 'offline'}
  {$lang.operators_offline}
{elseif $divert == 'busy'}
  {$lang.connection_failure}
  <br /><br />
  {$lang.operators_busy}
  <br /><br />
{elseif $divert == 'email_sent'}
  {$lang.email_sent}
{else}
  {$lang.error}
{/if}

{if $conf.modules.osTicket.active == true}
<b><a href="{$conf.url}/module.php?module=osTicket&file=/modules/osTicket/open.php" target="_blank">{$lang.use_tickets}</a></b>
{if $conf.forum_show == true}
<br/><br/><b><a href="{$conf.forum_url}" target="_blank">{$lang.forums}</a></b>
{/if}
{else}
{if $conf.forum_show == true}
<b><a href="{$conf.forum_url}" target="_blank">{$lang.forums}</a></b><br/><br/>
{/if}
<form action="{$_SERVER.PHP_SELF}" method="post">
  <table cellspacing="0" cellpadding="5" class="border">
    <tr class="dark">
      <td colspan="2">{$lang.contact_us}</td>
    </tr>
    {if $departmentid == '0'}
    <tr class="medium">
      <td>{$lang.department}</td>
      <td>
      <select name="departmentid">
        {section name='i' loop=$departments}
          <option value="{$departments[i].id}">{$departments[i].name}</option>
        {/section}
      </select>
      </td>
    </tr>
    {/if}
    <tr class="medium">
      <td>{$lang.your_name}</td>
      <td><input type="text" name="name" value="" size="20" /></td>
    </tr>
    <tr class="medium">
      <td>{$lang.your_email}</td>
      <td><input type="text" name="email" value="" size="20" /></td>
    </tr>
    <tr class="medium">
      <td>{$lang.subject}</td>
      <td><input type="text" name="subject" value="" size="20" /></td>
    </tr>
    <tr class="medium">
      <td>{$lang.message}</td>
      <td><textarea name="message" rows="5" cols="20"></textarea></td>
    </tr>
    <tr>
      <td colspan="2" align="center">{if $departmentid !== '0'}<input type="hidden" name="departmentid" value="{$departmentid}" />{/if}<input type="submit" name="email_send" value="{$lang.submit}" /></td>
    </tr>
  </table>
</form>
{/if}
</div>
</body>
</html>