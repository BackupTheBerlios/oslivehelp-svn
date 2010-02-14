<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$conf.company} - Live Support Solution (Powered By Help Center Live)</title>
<meta http-equiv="Content-Type" content="text/html; charset={$lang.charset}" />
<link href="{$conf.url}/templates/{$conf.template}/css/admin.css" rel="stylesheet" type="text/css" />
{if $javascript != ""}
<script type="text/javascript" language="javascript" src="{$conf.url}/class/js/include.php?{$javascript}">
</script>
{/if}
</head>
<body{$onload}>
<div align="center">
  <div id="page">
    <table cellpadding="0" cellspacing="0">
      <tr>
        <td id="top">
        </td>
      </tr>
    </table>
    <table cellpadding="0" cellspacing="0">
      <tr>
        <td id="logo">
        </td>
        <td id="links_top_left">
        </td>
        <td id="links_top_table">
          <ul>
          {section name=i loop=$links_top}
            <a href="{$links_top[i].url}"><li>{$links_top[i].title}</li></a>
          {/section}
          </ul>
        </td>
        <td id="links_top_right">
        </td>
      </tr>
    </table>
    <table cellpadding="0" cellspacing="0">
      <tr>
        <td id="links_bottom_left">
        </td>
        <td id="links_bottom_table">
          <ul>
          {section name=i loop=$links_bottom}
            <a href="{$links_bottom[i].url}"><li>{$links_bottom[i].title}</li></a>
          {/section}
          </ul>
        </td>
        <td id="links_bottom_right">
        </td>
      </tr>
    </table>
    <table cellpadding="0" cellspacing="0">
      <tr>
        <td id="bottom">
        </td>
      </tr>
    </table>
    <div id="content">
      <div id="main">
        <table cellpadding="0" cellspacing="0">
          <tr>
            <td id="main_table">
              <table cellpadding="0" cellspacing="0">
                <tr>
                  <td id="main_left">
                    <ul>
                    {section name=i loop=$links_main}
                      <a href="{$links_main[i].url}"><li>{$links_main[i].title}</li></a>
                    {/section}
                    </ul>
                  </td>
                </tr>
                <tr>
                  <td id="links_main_bottom">
                  </td>
                </tr>
              </table>
            </td>
            <td rowspan="3" id="main_right">
              <h2>{$title}</h2>
              {include file=$content}
            </td>
          </tr>
        </table>
      </div>
      <table cellpadding="0" cellspacing="0" id="footer">
        <tr>
          <td id="footer_left">
            {$lang.powered_by} <a href="http://www.helpcenterlive.com" target="_blank">Help Center Live</a> {$conf.version}<br />Copyright &copy; <a href="http://www.ubertec.co.uk">Help Center Live</a>. All Rights Reserved.
          </td>
          <td id="footer_right">
            <a href="{$conf.url}/admin/update.php" target="_blank"><img src="{$conf.url}/templates/{$conf.template}/images/hcl.gif" border="0" alt="Help Center Live" /></a>
            <a href="http://www.php.net/" target="_blank"><img src="{$conf.url}/templates/{$conf.template}/images/php.gif" border="0" alt="PHP" /></a>
            <a href="http://www.mysql.com/" target="_blank"><img src="{$conf.url}/templates/{$conf.template}/images/mysql.gif" border="0" alt="MySQL" /></a>
            <a href="http://smarty.php.net/" target="_blank"><img src="{$conf.url}/templates/{$conf.template}/images/smarty.gif" border="0" alt="Smarty" /></a>
          </td>
        </tr>
      </table>
    </div>
  </div>
</div>
</body>
</html>