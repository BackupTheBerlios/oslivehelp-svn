<div align="center">
{if $text !== ''}{$text}<br /><br />{/if}
{section name=i loop=$assigns}
    <form action="{$_SERVER.PHP_SELF}" method="post">
      <table cellspacing="0" cellpadding="0" class="list">
        <tr class="main">
          <td><b>{$assigns[i].name}</b></td>
        </tr>
      {section name=x loop=$assigns[i].operators}
        <tr class="{cycle values="alt,}">
          <td><input type="checkbox" name="{$assigns[i].operators[x].id}" value="{$assigns[i].id}" {if $assigns[i].operators[x].checked == 'true'}checked="checked"{/if} /> {$assigns[i].operators[x].firstname} {$assigns[i].operators[x].lastname} ({$assigns[i].operators[x].username}) - {$lang.priority}: <input type="text" name="poll_{$assigns[i].operators[x].id}" value="{$assigns[i].operators[x].poll}" size="5" /> {$lang.priority_rank}</td>
        </tr>
      {/section}
      </table>
      <br />
    <input type="hidden" name="id" value="{$assigns[i].id}" /><input type="submit" name="edit" value="{$lang.edit}" />
  </form>
  <br />
{/section}
</div>