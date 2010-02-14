{if $action == 'add'}
<form action="{$_SERVER.PHP_SELF}" method="post">
<table cellpadding="0" cellspacing="5">
  <tr>
    <td>{$lang.name}:</td>
    <td><input type="text" name="name" size="40" /></td>
  </tr>
  <tr>
    <td>{$lang.visible}:</td>
    <td><select name="visible"><option value="1">{$lang.true}</option><option value="0">{$lang.false}</option></select></td>
  </tr>
  <tr>
    <td>{$lang.order}:</td>
    <td><input type="text" name="order" size="4" />{$lang.priority_rank}</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="main">
    <td colspan="2" align="center">{$lang.email_info}</td>
  </tr>
  <tr>
    <td>{$lang.email}:</td>
    <td><input type="text" name="email" size="40" /></td>
  </tr>
  <tr>
    <td>{$lang.email_host}:</td>
    <td><input type="text" name="email_host" size="40" /></td>
  </tr>
  <tr>
    <td>{$lang.email_port}:</td>
    <td><input type="text" name="email_port" size="4" /></td>
  </tr>
  <tr>
    <td>{$lang.email_username}:</td>
    <td><input type="text" name="email_username" size="40" /></td>
  </tr>
  <tr>
    <td>{$lang.email_password}:</td>
    <td><input type="password" name="email_password" size="40" /></td>
  </tr>
  <tr>
    <td></td>
    <td><input type="submit" name="add" value="{$lang.add}" /></td>
  </tr>
</table>
</form>
{elseif $action == 'added'}
<div align="center">
{$lang.department_added}
</div>
{elseif $action == 'edit'}
<form action="{$_SERVER.PHP_SELF}" method="post">
<table cellpadding="0" cellspacing="5">
  <tr>
    <td>{$lang.name}:</td>
    <td><input type="text" name="name" value="{$department.name}" size="40" /></td>
  </tr>
  <tr>
    <td>{$lang.visible}:</td>
    <td><select name="visible"><option value="1"{if $department.visible == "1"} selected="selected"{/if}>{$lang.true}</option><option value="0"{if $department.visible == "0"} selected="selected"{/if}>{$lang.false}</option></select></td>
  </tr>
  <tr>
    <td>{$lang.order}:</td>
    <td><input type="text" name="order" value="{$department.order}" size="4" />{$lang.priority_rank}</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="main">
    <td colspan="2" align="center">{$lang.email_info}</td>
  </tr>
  <tr>
    <td>{$lang.email}:</td>
    <td><input type="text" name="email" value="{$department.email}" size="40" /></td>
  </tr>
  <tr>
    <td>{$lang.email_host}:</td>
    <td><input type="text" name="email_host" value="{$department.email_host}" size="40" /></td>
  </tr>
  <tr>
    <td>{$lang.email_port}:</td>
    <td><input type="text" name="email_port" value="{$department.email_port}" size="4" /></td>
  </tr>
  <tr>
    <td>{$lang.email_username}:</td>
    <td><input type="text" name="email_username" value="{$department.email_username}" size="40" /></td>
  </tr>
  <tr>
    <td>{$lang.email_password}:</td>
    <td><input type="password" name="email_password" value="{$department.email_password}" size="40" /></td>
  </tr>
  <tr>
    <td></td>
    <td><input type="hidden" name="id" value="{$department.id}" /><input type="submit" name="edit" value="{$lang.edit}" /></td>
  </tr>
</table>
</form>
{elseif $action == 'edited'}
<div align="center">
{$lang.department_edited}
</div>
{else}
<form action="{$_SERVER.PHP_SELF}" method="post" onsubmit="return Misc.confirm_action();">
<div align="center">
<table width="90%" border="0" cellspacing="0" cellpadding="2" align="center" class="border">
{section name=i loop=$departments}
  <tr class="medium" align="center">
    <td>{$departments[i].name} ({$departments[i].email}){if $departments[i].visible == "0"} - hidden{/if}</td>
    <td align="right"><a href="{$conf.url}/admin/departments.php?edit&id={$departments[i].id}">{$lang.edit}</a> | <a href="{$conf.url}/admin/icons.php?edit&id={$departments[i].id}">{$lang.icons}</a> | <input type="checkbox" name="{$departments[i].id}" value="{$departments[i].id}" /></td>
  </tr>
{/section}
</table>
<br /><br />
<input type="submit" name="delete" value="{$lang.delete}" />
<div>
</form>
{/if}