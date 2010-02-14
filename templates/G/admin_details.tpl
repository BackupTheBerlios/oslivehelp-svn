{if $alert !== ''}
<div align="center">
<b>{$alert}</b>
</div>
{else}
<form action="{$_SERVER.PHP_SELF}" method="post" enctype="multipart/form-data">
<div align="center">
<table cellpadding="2" cellspacing="0" class="list">
  <tr class="{cycle values="alt,"}">
    <td>{$lang.username}</td>
    <td>{$details.username}</td>
  </tr>
  <tr class="{cycle values="alt,"}">
    <td>{$lang.old_password}</td>
    <td><input type="password" name="old_password" size="20" /></td>
  </tr>
  <tr class="{cycle values="alt,"}">
    <td>{$lang.new_password}</td>
    <td><input type="password" name="new_password" size="20" /></td>
  </tr>
  <tr class="{cycle values="alt,"}">
    <td>{$lang.new_password_again}</td>
    <td><input type="password" name="new_password_again" size="20" /></td>
  </tr>
  <tr class="{cycle values="alt,"}">
    <td>{$lang.first_name}</td>
    <td><input type="text" name="firstname" value="{$details.firstname}" size="20" /></td>
  </tr>
  <tr class="{cycle values="alt,"}">
    <td>{$lang.last_name}</td>
    <td><input type="text" name="lastname" value="{$details.lastname}" size="20" /></td>
  </tr>
  <tr class="{cycle values="alt,"}">
    <td>{$lang.email}</td>
    <td><input type="text" name="email" value="{$details.email}" size="20" /></td>
  </tr>
  <tr class="{cycle values="alt,"}">
    <td>{$lang.picture}</td>
    <td>{if $details.picture !== ''}<img src="{$conf.url}/live/icon.php?picture&id={$id}" /> <br /><br />{/if}<input type="file" name="picture" /></td>
  </tr>
  <tr class="{cycle values="alt,"}">
    <td>{$lang.display_picture}</td>
    <td>
      <select name="showpic">
        <option value="1"{if $details.showpic == '1'} selected="selected"{/if}>{$lang.yes}</option>
        <option value="0"{if $details.showpic == '0'} selected="selected"{/if}>{$lang.no}</option>
      </select>
    </td>
  </tr>
  <tr class="{cycle values="alt,"}">
    <td>{$lang.autosave_transcripts}</td>
    <td>
      <select name="autosave">
        <option value="1"{if $details.autosave == '1'} selected="selected"{/if}>{$lang.yes}</option>
        <option value="0"{if $details.autosave == '0'} selected="selected"{/if}>{$lang.no}</option>
      </select>
    </td>
  </tr>
</table>
<br /><br />
<input type="submit" name="submit" value="{$lang.submit}" />
</div>
</form>
{/if}