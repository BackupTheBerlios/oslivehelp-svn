<div align="center">
{if $text !== ''}{$text}<br /><br />{/if}
<table width="90%" border="0" cellspacing="0" cellpadding="2" align="center" class="border">
{section name=i loop=$assigns}
  <tr class="{cycle values="light,medium}" align="center">
    <td>{$assigns[i].name}</td>
    <td>
      <form action="{$_SERVER.PHP_SELF}" method="post">
        <table width="100%" border="0" cellspacing="0" cellpadding="2">
      {section name=x loop=$assigns[i].operators}
          <tr>
            <td><input type="checkbox" name="{$assigns[i].operators[x].id}" value="{$assigns[i].id}" {if $assigns[i].operators[x].checked == 'true'}checked="checked"{/if} /> {$assigns[i].operators[x].firstname} {$assigns[i].operators[x].lastname} ({$assigns[i].operators[x].username}) - {$lang.priority}: <input type="text" name="poll_{$assigns[i].operators[x].id}" value="{$assigns[i].operators[x].poll}" size="5" /> {$lang.priority_rank}</td>
          </tr>
      {/section}
          <tr>
            <td><input type="hidden" name="id" value="{$assigns[i].id}" /><input type="submit" name="edit" value="{$lang.edit}" /></td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
{/section}
</table>
<div>