<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$conf.company} - Live Support Solution (Powered By Help Center Live)</title>
<meta http-equiv="Content-Type" content="text/html; charset={$lang.charset}" />
<link href="{$conf.url}/templates/{$conf.template}/css/chat.css" rel="stylesheet" type="text/css" />
</head>
<body{$onload}>

<br />
<div align="center">
<a href="{$conf.url}/live/chat/admin.php?info&amp;chatid={$chatid}">[{$lang.visitor_info}]</a>
<a href="{$conf.url}/live/chat/admin.php?canned&amp;chatid={$chatid}">[{$lang.canned_messages}]</a>
<a href="{$conf.url}/live/chat/admin.php?transfer&amp;chatid={$chatid}">[{$lang.transfer_chat}]</a>
<a href="{$conf.url}/live/chat/admin.php?file&amp;chatid={$chatid}">[{$lang.transfer_file}]</a>
</div>
<br /><br />

{if $type == 'canned'}

<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center" class="list">
  <tr class="main">
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center" colspan="2"><b>{$lang.canned_all}</b></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table width="100%" class="list" cellspacing="0" cellpadding=0">
{section name=i loop=$canned.all}
        <tr class="{cycle values="alt,"}" align="center"><td>{$canned.all[i].message}</td><td align="right">[<a href="javascript:parent.window.Chat.canned('{$canned.all[i].message_js}');">{$lang.send}</a>]</td></tr>
{/section}
      </table>
    </td>
  </tr>
</table><br /><br />

<table width="90%" border="0" cellspacing="0" cellpadding="2" align="center" class="list">
  <tr class="main">
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center" colspan="2"><b>{$lang.canned_op}</b></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
{section name=i loop=$canned.op}
        <tr class="{cycle values="alt,"}" align="center"><td>{$canned.op[i].message}</td><td align="right">[<a href="javascript:parent.window.Chat.canned('{$canned.op[i].message_js}');">{$lang.send}</a>]</td></tr>
{/section}
      </table>
    </td>
  </tr>
</table><br /><br />

<table width="90%" border="0" cellspacing="0" cellpadding="2" align="center" class="list">
  <tr class="main">
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center"><b>{$lang.canned_dep}</b></td><td align="center">&nbsp;
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="2" class="list">
{section name=i loop=$canned.dep}
        <tr class="{cycle values="alt,"}" align="center"><td>{$canned.dep[i].message}</td><td align="right">[<a href="javascript:parent.window.Chat.canned('{$canned.dep[i].message_js}');">{$lang.send}</a>]</td></tr>
{/section}
      </table>
    </td>
  </tr>
</table><br /><br />

<table width="90%" border="0" cellspacing="0" cellpadding="2" align="center" class="list">
  <tr class="main">
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center"><b>{$lang.canned_both}</b></td><td align="center">&nbsp;
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
{section name=i loop=$canned.both}
        <tr class="{cycle values="alt,"}" align="center"><td>{$canned.both[i].message}</td><td align="right">[<a href="javascript:parent.window.Chat.canned('{$canned.both[i].message_js}');">{$lang.send}</a>]</td></tr>
{/section}
      </table>
    </td>
  </tr>
</table><br /><br />

{elseif $type == 'transfer'}

{section name=i loop=$transfer}
<table width="90%" border="0" cellspacing="0" cellpadding="2" align="center" class="list">
  <tr class="main">
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center" colspan="2"><b>{$transfer[i].name} - {$transfer[i].email}</b></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="" class="list">
{section name=n loop=$transfer[i].operators}
{if $transfer[i].operators[n].status == 'true'}
        <tr class="{cycle values="alt,"}" align="center"><td>{$transfer[i].operators[n].name}</td><td align="right">[<a href="javascript:parent.window.Chat.transfer_chat('{$transfer[i].operators[n].id}', '{$transfer[i].id}');">{$lang.transfer}</a>]</td></tr>
{else}
        <tr class="{cycle values="alt,"}" align="center"><td>{$transfer[i].operators[n].name}</td><td align="right">[{$lang.offline}]</td></tr>
{/if}
{/section}
      </table>
    </td>
  </tr>
</table><br /><br />
{/section}

{elseif $type == 'file'}

{if $submit == 'true' && $fail == 'true'}
<div align="center"><b>{$lang.cannot_transfer_0}</b></div><br />
{/if}
{if $request_transfer == 'true'}
<div align="center"><b>{$lang.request_sent}</b></div><br />
{/if}
<div align="center">
<form name="upload" enctype="multipart/form-data" method="post" action="{$conf.url}/live/chat/admin.php?file&amp;chatid={$chatid}">
<table width="90%" align="center" border="0" cellspacing="0" cellpadding="0" class="list">
  <tr class="main">
    <td align="center"><b>{$lang.send_file}</b> ({$lang.max_size_is}: {$max_size})</td>
  </tr>
  <tr>
    <td align="right"><input type="file" name="file" /> <input type="submit" name="submit" value="{$lang.transfer}" /></td>
  </tr>
</table>
<br /><br />
<table width="90%" align="center" border="0" cellspacing="0" cellpadding="0" class="list">
  <tr class="main">
    <td colspan="2"><div align="center"><b><a href="{$conf.url}/live/chat/admin.php?file&amp;request_transfer&amp;chatid={$chatid}">{$lang.request_file}</a></b></div></td>
  </tr>
</table></form>
</div>
<br /><br />

{elseif $type == 'info'}

<table width="90%" align="center" border="0" cellspacing="0" cellpadding="0" class="list">
  <tr class="alt">
    <td colspan="2"><div align="center"><b>{$lang.hostname}</b></div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">{$info.hostname}</div></td>
  </tr>
  <tr class="alt">
    <td colspan="2"><div align="center"><b>{$lang.ip_addr}</b></div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">{$info.ip}</div></td>
  </tr>
  <tr class="alt">
    <td colspan="2">&nbsp;&nbsp;<b>{$lang.useragent}</b></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;&nbsp;{$info.useragent}</td>
  </tr>
  <tr class="alt">
    <td colspan="2">&nbsp;&nbsp;<b>{$lang.referrer}</b></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;&nbsp;<a href="{$info.refurl}" target="_blank">{$info.referrer}</a></td>
  </tr>
  <tr class="alt">
    <td colspan="2">&nbsp;&nbsp;<b>{$lang.current_page}</b></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;&nbsp;<a href="{$info.url}" target="_blank">{$info.page}</a></td>
  </tr>
  <tr class="alt">
    <td><div align="center"><b>{$lang.time_page}</b></div></td>
    <td><div align="center"><b>{$lang.time_site}</b></div></td>
  </tr>
  <tr>
    <td><div align="center">{$info.page_time}</div></td>
    <td><div align="center">{$info.site_time}</div></td>
  </tr>
  <tr class="alt">
    <td><div align="center"><b>{$lang.total_unique_visits}</b></div></td>
    <td><div align="center"><b>{$lang.total_chat_requests}</b></div></td>
  </tr>
  <tr>
    <td><div align="center">{$info.visits}</div></td>
    <td><div align="center">{$info.requests}</div></td>
  </tr>
  <tr class="alt">
    <td class="dark" colspan="2">&nbsp;&nbsp;<b>{$lang.footprints}</b></td>
  </tr>
  <tr>
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
  <tr class="alt">
    <td colspan="2">&nbsp;&nbsp;<b>{$lang.history}</b></td>
  </tr>
  <tr>
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
  <tr class="alt">
    <td colspan="3"><div align="center">[<a href="javascript:window.location.href = window.location.href+'&history';">{$lang.view_history}</a>]</div></td>
  </tr>
</table>
<br /><br />

{/if}

</body>
</html>