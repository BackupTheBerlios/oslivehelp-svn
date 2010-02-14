<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$conf.company} - Live Support Solution (Powered By Help Center Live)</title>
<meta http-equiv="Content-Type" content="text/html; charset={$lang.charset}" />
<link href="{$conf.url}/templates/{$conf.template}/css/monitor.css" rel="stylesheet" type="text/css" />
{if $javascript != ""}
<script type="text/javascript" language="javascript" src="{$conf.url}/class/js/include.php?{$javascript}">
</script>
{/if}
</head>
<body{$onload} id="content">
<br />


<table width="90%" align="center" border="0" cellspacing="0" cellpadding="0" class="list">
  <tr>
    <td colspan="2"><div align="center"><b>{$lang.hostname}</b></div></td>
  </tr>
  <tr class="alt">
    <td colspan="2"><div align="center">{$info.hostname}</div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center"><b>{$lang.ip_addr}</b></div></td>
  </tr>
  <tr class="alt">
    <td colspan="2"><div align="center">{$info.ip}</div></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;&nbsp;<b>{$lang.useragent}</b></td>
  </tr>
  <tr class="alt">
    <td colspan="2">&nbsp;&nbsp;{$info.useragent}</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;&nbsp;<b>{$lang.referrer}</b></td>
  </tr>
  <tr class="alt">
    <td colspan="2">&nbsp;&nbsp;<a href="{$info.refurl}" target="_blank">{$info.referrer}</a></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;&nbsp;<b>{$lang.current_page}</b></td>
  </tr>
  <tr class="alt">
    <td colspan="2">&nbsp;&nbsp;<a href="{$info.url}" target="_blank">{$info.page}</a></td>
  </tr>
  <tr>
    <td><div align="center"><b>{$lang.time_page}</b></div></td>
    <td><div align="center"><b>{$lang.time_site}</b></div></td>
  </tr>
  <tr class="alt">
    <td><div align="center">{$info.page_time}</div></td>
    <td><div align="center">{$info.site_time}</div></td>
  </tr>
  <tr>
    <td><div align="center"><b>{$lang.total_unique_visits}</b></div></td>
    <td><div align="center"><b>{$lang.total_chat_requests}</b></div></td>
  </tr>
  <tr class="alt">
    <td><div align="center">{$info.visits}</div></td>
    <td><div align="center">{$info.requests}</div></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;&nbsp;<b>{$lang.footprints}</b></td>
  </tr>
  <tr class="alt">
    <td colspan="2">
{section name=i loop=$info.footprints}
{if $info.footprints[i].hotpage == 'true'}
      <b>!</b>&nbsp;&nbsp;<a href="{$info.footprints[i].url}" target="_blank">{$info.footprints[i].page}</a> - {$info.footprints[i].time}<br />
{else}
      &nbsp;&nbsp;&nbsp;<a href="{$info.footprints[i].url}" target="_blank">{$info.footprints[i].page}</a> - {$info.footprints[i].time}<br />
{/if}
{/section}
    </td>
  </tr>
{if $history == 'true'}
  <tr>
    <td colspan="2">&nbsp;&nbsp;<b>{$lang.history}</b></td>
  </tr>
  <tr class="alt">
    <td colspan="2">
{section name=i loop=$info.history}
{if $info.history[i].hotpage == 'true'}
      <b>!</b>&nbsp;&nbsp;<a href="{$info.history[i].url}" target="_blank">{$info.history[i].page}</a> - {$info.history[i].time}<br />
{else}
      &nbsp;&nbsp;&nbsp;<a href="{$info.history[i].url}" target="_blank">{$info.history[i].page}</a> - {$info.history[i].time}<br />
{/if}
{/section}
    </td>
  </tr>
{/if}
  <tr>
    <td colspan="2"><div align="center">[<a href="javascript:window.location.href = window.location.href+'&history';">{$lang.view_history}</a>][<a href="javascript:window.close();">{$lang.close}</a>]</div></td>
  </tr>
</table>
<br /><br />

</body>
</html>