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
<body>
<div align="center">
  <div id="page">
    <table cellpadding="0" cellspacing="0">
      <tr>
        <td id="bottom">
        </td>
      </tr>
    </table>
    <div id="content">
      <div>
        <table cellpadding="0" cellspacing="0">
          <tr>
            <td rowspan="3" id="main_right">
              <h2>{$title}</h2>
              {include file=$content}
            </td>
          </tr>
        </table>
      </div>
      <table cellpadding="0" cellspacing="0" id="footer">
        <tr>
          <td>
          </td>
        </tr>
      </table>
    </div>
  </div>
</div>
</body>
</html>