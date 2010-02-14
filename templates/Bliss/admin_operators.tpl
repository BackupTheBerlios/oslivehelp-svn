{if $action == 'add'}

<form action="{$_SERVER.PHP_SELF}" method="post" enctype="multipart/form-data">
<div align="center">
{if $error !== ''}<b>{$error}</b><br /><br />{/if}
<table border="0" cellspacing="0" cellpadding="2" class="border">
  <tr class="{cycle values="light,medium"}">
    <td>{$lang.username}</td>
    <td><input type="text" name="username" size="20" value="{$username}" /></td>
  </tr>
  <tr class="{cycle values="light,medium"}">
    <td>{$lang.new_password}</td>
    <td><input type="password" name="new_password" size="20" value="" /></td>
  </tr>
  <tr class="{cycle values="light,medium"}">
    <td>{$lang.new_password_again}</td>
    <td><input type="password" name="new_password_again" size="20" value="" /></td>
  </tr>
  <tr class="{cycle values="light,medium"}">
    <td>{$lang.first_name}</td>
    <td><input type="text" name="firstname" size="20" value="{$firstname}" /></td>
  </tr>
  <tr class="{cycle values="light,medium"}">
    <td>{$lang.last_name}</td>
    <td><input type="text" name="lastname" size="20" value="{$lastname}" /></td>
  </tr>
  <tr class="{cycle values="light,medium"}">
    <td>{$lang.email}</td>
    <td><input type="text" name="email" size="20" value="{$email}" /></td>
  </tr>
  <tr class="{cycle values="light,medium"}">
    <td>{$lang.admin}</td>
    <td>
      <select name="admin">
        <option value="0"{if $admin == '0'} selected="selected"{/if}>{$lang.yes}</option>
        <option value="1"{if $admin == '1' || $admin == ''} selected="selected"{/if}>{$lang.no}</option>
      </select>
    </td>
  </tr>
  <tr class="{cycle values="light,medium"}">
    <td>{$lang.autosave_transcripts}</td>
    <td>
      <select name="autosave">
        <option value="1"{if $autosave == '1' || $autosave == ''} selected="selected"{/if}>{$lang.yes}</option>
        <option value="0"{if $autosave == '0'} selected="selected"{/if}>{$lang.no}</option>
      </select>
    </td>
  </tr>
  <tr class="{cycle values="light,medium"}">
    <td>{$lang.display_picture}</td>
    <td>
      <select name="showpic">
        <option value="1"{if $showpic == '1'} selected="selected"{/if}>{$lang.yes}</option>
        <option value="0"{if $showpic == '0' || $showpic == ''} selected="selected"{/if}>{$lang.no}</option>
      </select>
    </td>
  </tr>
  <tr class="{cycle values="light,medium"}">
    <td>{$lang.picture}</td>
    <td><input type="file" name="picture" /></td>
  </tr>
</table>
<br /><br />
<input type="submit" name="add" value="{$lang.add}" />
</div>
</form>

{elseif $action == 'added'}

<div align="center">
<b>{$lang.operator_added}</b>
</div>

{elseif $action == 'edit'}

<form action="{$_SERVER.PHP_SELF}" method="post" enctype="multipart/form-data">
<div align="center">
<table border="0" cellspacing="0" cellpadding="2" class="border">
  <tr class="{cycle values="light,medium"}">
    <td>{$lang.username}</td>
    <td><input type="text" name="username" size="20" value="{$operator.username}" /></td>
  </tr>
  <tr class="{cycle values="light,medium"}">
    <td>{$lang.new_password}</td>
    <td><input type="password" name="new_password" size="20" value="" /></td>
  </tr>
  <tr class="{cycle values="light,medium"}">
    <td>{$lang.new_password_again}</td>
    <td><input type="password" name="new_password_again" size="20" value="" /></td>
  </tr>
  <tr class="{cycle values="light,medium"}">
    <td>{$lang.first_name}</td>
    <td><input type="text" name="firstname" size="20" value="{$operator.firstname}" /></td>
  </tr>
  <tr class="{cycle values="light,medium"}">
    <td>{$lang.last_name}</td>
    <td><input type="text" name="lastname" size="20" value="{$operator.lastname}" /></td>
  </tr>
  <tr class="{cycle values="light,medium"}">
    <td>{$lang.email}</td>
    <td><input type="text" name="email" size="20" value="{$operator.email}" /></td>
  </tr>
  <tr class="{cycle values="light,medium"}">
    <td>{$lang.admin}</td>
    <td>
      <select name="admin">
        <option value="0"{if $operator.level == '0'} selected="selected"{/if}>{$lang.yes}</option>
        <option value="1"{if $operator.level == '1'} selected="selected"{/if}>{$lang.no}</option>
      </select>
    </td>
  </tr>
  <tr class="{cycle values="light,medium"}">
    <td>{$lang.autosave_transcripts}</td>
    <td>
      <select name="autosave">
        <option value="1"{if $operator.autosave == '1'} selected="selected"{/if}>{$lang.yes}</option>
        <option value="0"{if $operator.autosave == '0'} selected="selected"{/if}>{$lang.no}</option>
      </select>
    </td>
  </tr>
  <tr class="{cycle values="light,medium"}">
    <td>{$lang.display_picture}</td>
    <td>
      <select name="showpic">
        <option value="1"{if $operator.showpic == '1'} selected="selected"{/if}>{$lang.yes}</option>
        <option value="0"{if $operator.showpic == '0'} selected="selected"{/if}>{$lang.no}</option>
      </select>
    </td>
  </tr>
  <tr class="{cycle values="light,medium"}">
    <td>{$lang.picture}</td>
    <td>{if $operator.picture !== ''}<img src="{$conf.url}/live/icon.php?picture&id={$operator.id}" /> <br /><br />{/if}<input type="file" name="picture" /></td>
  </tr>
</table>
<br /><br />
<input type="hidden" name="id" value="{$operator.id}" />
<input type="submit" name="edit" value="{$lang.edit}" />
</div>
</form>

{elseif $action == 'edited'}

<div align="center">
<b>{$text}</b>
</div>

{elseif $action == 'delete'}

<div align="center">
<b>{$lang.operators_deleted}</b>
</div>

{elseif $action == 'boot'}

<form action="{$_SERVER.PHP_SELF}" method="post">
<div align="center">
<b>{$alert}</b><br /><br />
<input type="hidden" name="id" value="{$id}" />
<input type="submit" name="boot" value="{$lang.boot}" />
</div>
</form>

{elseif $action == 'view'}

<form action="{$_SERVER.PHP_SELF}" method="post" onsubmit="return Misc.confirm_action();">
<table width="90%" border="0" cellspacing="0" cellpadding="2" align="center" class="border">
{section name='i' loop=$operators}
  <tr class="{cycle values="light,medium"}">
    <td>{$operators[i].firstname} {$operators[i].lastname} ({$operators[i].username})</td>
    <td align="right">
    {if $operators[i].monitor_status == 'web'}
     | <a href="{$conf.url}/admin/operators.php?boot&id={$operators[i].id}">{$lang.monitor_web}</a>
    {elseif $operators[i].monitor_status == 'client'}
     | <a href="{$conf.url}/admin/operators.php?boot&id={$operators[i].id}">{$lang.monitor_client}</a>
    {else}
     | {$lang.monitor_none}
    {/if}
     | <a href="{$conf.url}/admin/stats.php?operators&id={$operators[i].id}">{$lang.view_stats}</a> | <a href="{$conf.url}/admin/operators.php?edit&id={$operators[i].id}">{$lang.edit}</a> | <input type="checkbox" name="{$operators[i].id}" value="{$operators[i].id}" /></td>
  </tr>
{/section}
</table>
<br /><br />
<div align="center">
<input type="submit" name="delete" value="{$lang.delete}" />
<div>
</form>
<br /><br />
<div align="center">
<input type="button" name="boot" value="{$lang.boot_all}" onclick="window.location.href='{$conf.url}/admin/operators.php?boot';" />
<div>

{/if}