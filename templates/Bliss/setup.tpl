<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Help Center Live - Install / Upgrade</title>
<meta http-equiv="Content-Type" content="text/html; charset={$lang.charset}" />
<link href="../templates/{$conf.template}/css/setup.css" rel="stylesheet" type="text/css" />
</head>
<body>
<br /><br />
<div align="center">
{if $step == '1'}
<form action="{$_SERVER.PHP_SELF}" method="post">
  <table cellspaing="0" cellpadding="5" class="border">
    <tr class="dark">
      <td colspan="2"><b>{$lang.step1}</b></td>
    </tr>
    <tr class="medium">
      <td colspan="2" width="400">{$lang.readme}</td>
    </tr>
    <tr class="medium">
      <td>{$lang.select_lang}:</td>
      <td>
        <select name="language">
        {section name=i loop=$language}
          <option value="{$language[i].file}"{if $language[i].file == $lang_file} selected="selected"{/if}>{$language[i].name}</option>
        {/section}
        </select>
      </td>
    </tr>
    <tr>
      <td colspan="2" align="center"><input type="hidden" name="one" value="one" /><input type="submit" name="submit" value="{$lang.submit}" /> <input type="submit" name="skip" value="{$lang.skip}" /></td>
    </tr>
  </table>
</form>
<br /><br />
  <table cellspaing="0" cellpadding="5" width="300" class="border">
    <tr class="dark">
      <td colspan="2"><b>{$lang.setup_arrive}</b></td>
    </tr>
  </table>
{elseif $step == '2'}
<form action="{$_SERVER.PHP_SELF}" method="post" name="two">
  <table cellspaing="0" cellpadding="5" class="border">
    <tr class="dark">
      <td colspan="2"><b>{$lang.step2}</b></td>
    </tr>
    <tr class="medium">
      <td colspan="2" align="center">{$lang.config_more}<br />{$lang.config_manual}</td>
    </tr>
    <tr>
      <td colspan="2" align="center"><input type="submit" name="skip" value="{$lang.skip}" /></td>
    </tr>
    <tr class="medium">
      <td colspan="2">{$lang.config_chmod}</td>
    </tr>
    <tr class="medium">
      <td colspan="2">{$lang.database}:</td>
    </tr>
    <tr class="light">
      <td>{$lang.hostname}:</td>
      <td><input type="text" name="host" value="localhost" size="20" /></td>
    </tr>
    <tr class="light">
      <td>{$lang.database}:</td>
      <td><input type="text" name="database" value="" size="20" /></td>
    </tr>
    <tr class="light">
      <td>{$lang.username}:</td>
      <td><input type="text" name="username" value="" size="20" /></td>
    </tr>
    <tr class="light">
      <td>{$lang.password}:</td>
      <td><input type="password" name="password" value="" size="20" /></td>
    </tr>
    <tr class="light">
      <td>{$lang.prefix}:</td>
      <td><input type="text" name="prefix" value="hcl_" size="20" /></td>
    </tr>
    <tr class="medium">
      <td colspan="2">{$lang.installation}:</td>
    </tr>
    <tr class="light">
      <td>{$lang.url}:</td>
      <td><input type="text" name="url" value="{$url}" size="30" /></td>
    </tr>
    <tr class="light">
      <td>{$lang.monitor_traffic}:</td>
      <td>
      <select name="monitor_traffic">
        <option value="true">{$lang.monitor_yes}</option>
        <option value="false">{$lang.monitor_no}</option>
      </td>
    </tr>
    <tr class="light">
      <td>{$lang.company_name}:</td>
      <td><input type="text" name="company" value="" size="20" /></td>
    </tr>
    <tr class="light">
      <td>{$lang.template}:</td>
      <td>
        <select name="template">
        {section name=i loop=$template}
          <option value="{$template[i].dir}"{if $template[i].dir == $template_dir} selected="selected"{/if}>{$template[i].name}</option>
        {/section}
        </select>
      </td>
    </tr>
    <tr>
      <td colspan="2" align="center"><input type="hidden" name="two" value="two" /><input type="submit" name="submit" value="{$lang.submit}" /></td>
    </tr>
  </table>
</form>
{elseif $step == '3'}
<form action="{$_SERVER.PHP_SELF}" method="post" name="three">
  <table cellspaing="0" cellpadding="5" width="300" class="border">
    <tr class="dark">
      <td colspan="2"><b>{$lang.step3}</b></td>
    </tr>
    <tr class="medium">
      <td colspan="2"><i><b>{$lang.upgrade_warning}</b></i></td>
    </tr>
    <tr class="medium">
      <td>{$lang.install_upgrade}:</td>
      <td>
        <select name="install_upgrade">
          <option value="install"{if $install_upgrade == 'install'} selected="selected"{/if}>{$lang.install}</option>
          <option value="upgrade"{if $install_upgrade == 'upgrade'} selected="selected"{/if}>{$lang.upgrade}</option>
        </select>
      </td>
    </tr>
    <tr>
      <td colspan="2" align="center"><input type="hidden" name="three" value="three" /><input type="submit" name="submit" value="{$lang.submit}" /></td>
    </tr>
  </table>
</form>
{elseif $step == '4'}
<form action="{$_SERVER.PHP_SELF}" method="post" name="four">
  <table cellspaing="0" cellpadding="5" class="border">
    <tr class="dark">
      <td colspan="2"><b>{$lang.step4}</b></td>
    </tr>
{if $install_upgrade == 'install'}
    <tr class="medium">
      <td colspan="2">{$lang.install_db}</td>
    </tr>
    <tr class="medium">
      <td>{$lang.database}: </td>
      <td>{$conf.database}</td>
    </tr>
    <tr class="medium">
      <td>{$lang.prefix}: </td>
      <td>{$conf.prefix}</td>
    </tr>
{elseif $install_upgrade == 'upgrade'}
    <tr class="medium">
      <td colspan="2">{$lang.upgrade_db}</td>
    </tr>
    <tr class="medium">
      <td>{$lang.database}: </td>
      <td>{$conf.database}</td>
    </tr>
{/if}
    <tr>
      <td colspan="2" align="center"><input type="hidden" name="four" value="four" /><input type="hidden" name="install_upgrade" value="{$install_upgrade}" /><input type="submit" name="submit" value="{$lang.submit}" /> <input type="submit" name="skip" value="{$lang.skip}" /></td>
    </tr>
  </table>
</form>
{elseif $step == '5'}
<form action="{$_SERVER.PHP_SELF}" method="post" name="five">
  <table cellspaing="0" cellpadding="5" class="border">
    <tr class="dark">
      <td colspan="2"><b>{$lang.step5}</b></td>
    </tr>
    <tr class="medium">
      <td colspan="2">{$lang.config_warning}</td>
    </tr>
    <tr>
      <td colspan="2" align="center"><input type="hidden" name="five" value="five" /><input type="submit" name="submit" value="{$lang.continue}" /></td>
    </tr>
    <tr class="medium">
      <td colspan="2">{$lang.config_problems}</td>
    </tr>
    <tr>
      <td colspan="2" align="center"><input type="submit" name="skip" value="{$lang.skip}" /></td>
    </tr>
  </table>
</form>
{elseif $step == '6'}
<form action="{$_SERVER.PHP_SELF}" method="post" name="six">
  <table cellspaing="0" cellpadding="5" class="border">
    <tr class="dark">
      <td colspan="2"><b>{$lang.step6}</b></td>
    </tr>
    <tr class="medium">
      <td colspan="2"><b>{$lang.setup_warning_login}</b></td>
    </tr>
    <tr class="medium">
      <td colspan="2">{$lang.setup_complete}</td>
    </tr>
    <tr class="medium">
      <td colspan="2">{$lang.now_login}</td>
    </tr>
    <tr class="light">
      <td>{$lang.username}: </td>
      <td>admin</td>
    </tr>
    <tr class="light">
      <td>{$lang.password}: </td>
      <td>admin</td>
    </tr>
    <tr class="medium">
      <td colspan="2" align="center"><a href="{$conf.url}/admin/">{$conf.url}/admin/</a></td>
    </tr>
  </table>
</form>
{/if}
</div>
</body>
</html>