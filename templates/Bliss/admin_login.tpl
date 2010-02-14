<b>{$login_text}</b><br />
<form method="post" action="{$_SERVER.PHP_SELF}">
<table style="padding-left: 50px;">
  <tr>
    <td>{$lang.username}:</td>
    <td><input type="text" name="username" id="username" /></td>
  </tr>
  <tr>
    <td>{$lang.password}:</td>
    <td><input type="password" name="password" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right"><input type="submit" value="{$lang.login}" /></td>
  </tr>
</table>
</form>
{literal}
<script language="Javascript" type="text/javascript">
focusElement('username');
</script>
{/literal}