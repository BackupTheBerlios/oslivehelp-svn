<form action="{$_SERVER.PHP_SELF}?id={$id}" method="post" enctype="multipart/form-data">
<table cellpadding="5" cellspacing="0" class="border">
  <tr class="dark">
    <td colspan="2" align="center">{$name}</td>
  </tr>
  <tr class="medium">
    <td>{$lang.online}:</td>
    <td><img src="{$conf.url}/live/icon.php?status=online&departmentid={$id}" alt="{$name}" /></td>
  </tr>
  <tr class="medium">
    <td>{$lang.use_default_icon}:</td>
    <td><input type="checkbox" name="default_online" value="true" /></td>
  </tr>
  <tr class="medium">
    <td>{$lang.use_department_icon}:</td>
    <td><input type="file" name="online" /></td>
  </tr>
  <tr class="light">
    <td>{$lang.offline}:</td>
    <td><img src="{$conf.url}/live/icon.php?status=offline&departmentid={$id}" alt="{$name}" /></td>
  </tr>
  <tr class="light">
    <td>{$lang.use_default_icon}:</td>
    <td><input type="checkbox" name="default_offline" value="true" /></td>
  </tr>
  <tr class="light">
    <td>{$lang.use_department_icon}:</td>
    <td><input type="file" name="offline" /></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="hidden" name="id" value="{$id}" /><input type="submit" name="edit" value="{$lang.submit}"></td>
  </tr>
</table>
</form>
<form action="{$_SERVER.PHP_SELF}" method="get">
<select name="id">
  <option value="0">{$lang.default}</option>
{section name='i' loop=$departments}
  <option value="{$departments[i].id}"{if $id == $departments[i].id} selected="selected"{/if}>{$departments[i].name}</option>
{/section}
</select>
<input type="submit" name="submit" value="{$lang.edit}">
</form>