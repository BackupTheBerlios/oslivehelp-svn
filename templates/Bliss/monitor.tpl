<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
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
<frameset rows="60,*,0" cols="*" framespacing="0" border="0">
  <frame src="{$conf.url}/admin/monitor/status.php?time={$epoch}" name="winstatus" frameborder="no" scrolling="no" noresize>
  <frame src="{$conf.url}/admin/monitor/response.php" name="monitor" frameborder="no" scrolling="yes">
</frameset>
</html>