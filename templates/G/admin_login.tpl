<div align="center">
<b>{$login_text}</b><br />
<form method="post" action="{$_SERVER.PHP_SELF}">
<div id="small">
<table cellpadding="0" cellspacing="0" class="list">
  <tr class="alt">
    <td>{$lang.username}:</td>
    <td><input type="text" name="username" id="username" /></td>
  </tr>
  <tr>
    <td>{$lang.password}:</td>
    <td><input type="password" name="password" /></td>
  </tr>
</table>
</div>
<br /><input type="submit" value="{$lang.login}" />
</form>
</div>
{literal}
<script language="Javascript" type="text/javascript">
focusElement('username');
</script>
{/literal}