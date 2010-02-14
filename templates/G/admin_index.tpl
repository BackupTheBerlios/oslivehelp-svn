{* we only want to display the correct content depending on what {$action} tells us
   so all the different sections are captured and then a simple if/elseif/else goes
   through each option that is avaliable and acts on what {$action} is saying *}
{capture name=add_note}
<form method="post" action="{$_SERVER.PHP_SELF}">
<table class="list" width="100%" cellspacing="0" cellpadding="0">
  <tr class="alt">
    <td>
      {$lang.subject}
    </td>
    <td>
      <input type="text" name="subject" />
    </td>
  </tr>
  <tr class="alt">
    <td>
      {$lang.message}
    </td>
    <td>
      <textarea cols="30" rows="5" name="message"></textarea>
    </td>
  </tr>
  <tr>
    <td>
      &nbsp;
    </td>
    <td>
      <input type="submit" name="add" value="{$lang.submit}" />
    </td>
  </tr>
</table>
</form>
{/capture}

{capture name=edit_note}
<form method="post" action="{$_SERVER.PHP_SELF}">
<table class="list" width="100%" cellspacing="0" cellpadding="0">
  <tr class="alt">
    <td>
      {$lang.subject}
    </td>
    <td>
      <input type="text" name="subject" value="{$subject}" />
    </td>
  </tr>
  <tr class="alt">
    <td>
      {$lang.message}
    </td>
    <td>
      <textarea cols="30" rows="5" name="message">{$message}</textarea>
    </td>
  </tr>
  <tr>
    <td>
      &nbsp;
    </td>
    <td>
      <input type="hidden" name="id" value="{$id}" /><input type="submit" name="edit" value="{$lang.submit}" />
    </td>
  </tr>
</table>
</form>
{/capture}

{capture name=delete_note}
<form method="post" action="{$_SERVER.PHP_SELF}">
<table class="list" width="100%" cellspacing="0" cellpadding="0">
  <tr class="alt">
    <td>
      {$lang.note_confirm_delete} &quot;<i>{$subject}</i>&quot;
    </td>
  </tr>
  <tr>
    <td>
      <input type="hidden" name="id" value="{$id}" /><input type="submit" name="delete" value="{$lang.delete}" /><input type="submit" name="cancel" value="{$lang.cancel}" />
    </td>
  </tr>
</table>
</form>
{/capture}

{capture name=view_note}
    {if $notes == "false"}
        {$lang.no_notes}
    {else}
        {section name=i loop=$notes}
<table class="list" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td>
      <table width="100%" class="list" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            {$notes[i].subject}
          </td>
          <td align="right">
            {$notes[i].time}
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
  <tr class="alt">
    <td>
      {$notes[i].message}
    </td>
  </tr>
  <tr>
    <td align="right">
      <a href="{$_SERVER.PHP_SELF}?edit={$notes[i].id}">{$lang.edit}</a> | <a href="{$_SERVER.PHP_SELF}?delete={$notes[i].id}">{$lang.delete}</a>
    </td>
  </tr>
</table><br />
        {/section}
    {/if}
{/capture}

{if $action == "add_note"}
    {$smarty.capture.add_note}
{elseif $action == "edit_note"}
    {$smarty.capture.edit_note}
{elseif $action == "delete_note"}
    {$smarty.capture.delete_note}
{else}
    {$smarty.capture.view_note}
{/if}