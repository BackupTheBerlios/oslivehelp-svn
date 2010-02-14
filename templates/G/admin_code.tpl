<div align="center">
{if $action == 'text'}
<b>{$lang.text_code}</b>
{elseif $action == 'image'}
<b>{$lang.image_code}</b>
{elseif $action == 'html'}
<b>{$lang.html_code}</b>
{elseif $action == 'invisible'}
<b>{$lang.invisible_code}</b>
{/if}
<br /><br />
<form action="{$_SERVER.PHP_SELF}?{if $action == 'image'}image{elseif $action == 'html'}html{elseif $action == 'text'}text{elseif $action == 'invisible'}invisible{/if}" method="post">
<table cellpadding="5" cellspacing="0" class="list">
  <tr class="alt">
    <td>{$lang.select_department}</td>
    <td>
      <select name="departmentid">
        <option value="0">{$lang.all_departments}</option>
        {section name='i' loop=$departments}
        <option value="{$departments[i].id}"{if $departmentid == $departments[i].id} selected="selected"{/if}>{$departments[i].name}</option>
        {/section}
      </select>
    </td>
  </tr>
  {if $action !== 'html'}
  <tr>
    <td>{$lang.code_cobrowse}</td>
    <td><input type="checkbox" name="cobrowse" value="1" {if $cobrowse == '1'}checked="checked"{/if}></td>
  </tr>
  {/if}
</table>
<br />
<input type="submit" name="submit" value="{$lang.generate_code}">
</form>
{if $departmentid !== ''}
  {if $action == 'html'}
<textarea name="all" cols="50" rows="5" id="all">
<!-- BEGIN Help Center Live Code, Copyright (c) 2005 Help Center Live. All Rights Reserved -->
<a href="{$conf.url}/live/main.php" target="_blank"><img src="{$conf.url}/live/icon.php{if $departmentid !== '0'}?departmentid={$departmentid}{/if}" alt="{$lang.click_for_live_help}" /></a>
<!-- END Help Center Live Code, Copyright (c) 2005 Help Center Live. All Rights Reserved -->
</textarea>
  {else}
<textarea name="all" cols="50" rows="5" id="all">
<!-- BEGIN Help Center Live Code, Copyright (c) 2005 Help Center Live. All Rights Reserved -->
<div id="div_initiate" style="position:absolute; z-index:1; top: 40%; left:40%; visibility: hidden;"><a href="javascript:Live.initiate_accept();"><img src="{$conf.url}/templates/{$conf.template}/images/initiate.gif" border="0"></a><br><a href="javascript:Live.initiate_decline();"><img src="{$conf.url}/templates/{$conf.template}/images/initiate_close.gif" border="0"></a></div>
<script type="text/javascript" language="javascript" src="{$conf.url}/class/js/include.php?live{if $action == 'text'}&text{/if}{if $action == 'invisible'}&invisible{/if}{if $cobrowse == '1'}&cobrowse{/if}{if $departmentid !== '0'}&departmentid={$departmentid}{/if}"></script>
<!-- END Help Center Live Code, Copyright (c) 2005 Help Center Live. All Rights Reserved -->
</textarea>
  {/if}
{/if}
</div>