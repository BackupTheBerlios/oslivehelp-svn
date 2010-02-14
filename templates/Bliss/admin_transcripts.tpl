{if $action == 'view'}
<table cellspacing="10" cellpadding="0" class="large">
  <tr>
    <td>{$lang.nick}: </td>
    <td>{$transcript.nick}</td>
  </tr>
  <tr>
    <td>{$lang.operator}: </td>
    <td>{$transcript.operator}</td>
  </tr>
  <tr>
    <td>{$lang.department}: </td>
    <td>{$transcript.department}</td>
  </tr>
  <tr>
    <td>{$lang.email}: </td>
    <td>{$transcript.email}</td>
  </tr>
  <tr>
    <td>{$lang.time}: </td>
    <td>{$transcript.time}</td>
  </tr>
</table>
<br />
<table cellspacing="10" cellpadding="0" class="large">
  <tr>
    <td>
      {$transcript.chat}
    </td>
  </tr>
</table>
<br /><br />
<form action="{$_SERVER.PHP_SELF}{if $admin == 'admin'}?admin{/if}" method="post" onsubmit="return Misc.confirm_action();">
<div align="center"><input type="hidden" name="{$transcript.id}" value="{$transcript.id}" /><input type="submit" name="delete" value="{$lang.delete}" /></div>
</form>
{else}
<form action="{$_SERVER.PHP_SELF}{if $admin == 'admin'}?admin{/if}" method="post" onsubmit="return Misc.confirm_action();">
<div align="center">

{section name=x loop=$transcripts}
<table width="90%" border="0" cellspacing="0" cellpadding="2" align="center" class="border">
  <tr class="dark">
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center"><b>{$transcripts[x].name}</b></td><td align="center">&nbsp;
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
    {section name=i loop=$transcripts[x].transcript}
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr class="{cycle values="light,medium"}"><td><a href="{$conf.url}/admin/transcripts.php?view{if $admin == 'admin'}&admin{/if}&id={$transcripts[x].transcript[i].id}">{$transcripts[x].transcript[i].time} - {$transcripts[x].transcript[i].hostname}</a></td><td align="right"> <input type="checkbox" name="{$transcripts[x].transcript[i].id}" value="{$transcripts[x].transcript[i].id}" /></td></tr>
      </table>
    {/section}
    </td>
  </tr>
</table><br /><br />
{/section}

<input type="submit" name="delete" value="{$lang.delete}" />
</div>
</form>
{/if}