{if $action == 'add'}
{$lang.hotpage_note}<br /><br /><br />
<div align="center">
<form action="{$_SERVER.PHP_SELF}" method="post">
<table cellpadding="0" cellspacing="0" class="list">
  <tr class="alt">
    <td>{$lang.page}:</td>
    <td><input type="text" name="page" value="{$hotpage.page}" size="40" /></td>
  </tr>
</table>
<br />
<input type="submit" name="add" value="{$lang.add}" />
</form>
</div>
{elseif $action == 'added'}
<div align="center">
{$lang.hotpage_added}
</div>
{elseif $action == 'edit'}
{$lang.hotpage_note}<br /><br /><br />
<div align="center">
<form action="{$_SERVER.PHP_SELF}" method="post">
<table cellpadding="0" cellspacing="0" class="list">
  <tr class="alt">
    <td>{$lang.page}:</td>
    <td><input type="text" name="page" value="{$hotpage.page}" size="40" /></td>
  </tr>
</table>
<br />
<input type="hidden" name="id" value="{$hotpage.id}" /><input type="submit" name="edit" value="{$lang.edit}" />
</form>
</div>
{elseif $action == 'edited'}
<div align="center">
{$lang.hotpage_edited}
</div>
{else}
<form action="{$_SERVER.PHP_SELF}" method="post" onsubmit="return Misc.confirm_action();">
<div align="center">
<table width="90%" border="0" cellspacing="0" cellpadding="2" align="center" class="list">
{section name=i loop=$hotpages}
  <tr class="{cycle values="alt,"}" align="center">
    <td>{$hotpages[i].page}</td>
    <td align="right"><a href="{$conf.url}/admin/hotpages.php?edit&id={$hotpages[i].id}">{$lang.edit}</a> | <input type="checkbox" name="{$hotpages[i].id}" value="{$hotpages[i].id}" /></td>
  </tr>
{/section}
</table>
<br />
<input type="submit" name="delete" value="{$lang.delete}" />
</div>
</form>
{/if}