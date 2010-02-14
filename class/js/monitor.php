    //=======================================================================

    // Copyright (c) 2005 Help Center Live. All Rights Reserved

    // This file is part of Help Center Live.

    // Help Center Live is free software; you can redistribute it and/or modify
    // it under the terms of the GNU General Public License as published by
    // the Free Software Foundation; either version 2 of the License, or
    // (at your option) any later version.

    // Help Center Live is distributed in the hope that it will be useful,
    // but WITHOUT ANY WARRANTY; without even the implied warranty of
    // MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    // GNU General Public License for more details.

    // You should have received a copy of the GNU General Public License
    // along with Help Center Live; if not, write to the Free Software
    // Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

    // Contributors: Michael Bird

    // File Comments:
    // This file contains all the javascript used at the admin end except
    // for the request monitor

    // This is a compressed version for faster page loading.
    // The normal vesrion is in the /normal directory

var MonitorConnection1 = new Aardvark('MonitorConnection1'); var MonitorConnection2 = new Aardvark('MonitorConnection2'); function Monitor() { this.time = Misc.epoch(); this.mac = navigator.platform.indexOf('Mac'); this.image_width = 0; this.request = false; this.operatorid = '<?php echo($operatorid); ?>'; this.launch = function()
{ window.open('<?php echo($GLOBALS['conf']['url']); ?>/admin/monitor/index.php', '', 'toolbar=no, status=no, scrollbars=no, resizable=yes, location=no, menubar=no, directories=no, width=<?php echo($GLOBALS['conf']['monitor_width']); ?>, height=<?php echo($GLOBALS['conf']['monitor_height']); ?>');}
this.check = function(variables)
{ var response = ''; if (MonitorConnection1.vars.monitor_<?php echo($operatorid); ?>) { response = MonitorConnection1.vars.monitor_<?php echo($operatorid); ?>; this.time = Misc.epoch(); this.refreshed = false; var i = 0; var max = 0; var action = response.split('|'); max = action.length - 1; for (i = 0; i < max; ++i) { switch (action[i]) { case 'request-incoming':
this.request = true; if (!this.refreshed) { this.refreshed = true; parent.window.monitor.location.href = '<?php echo($GLOBALS['conf']['url']); ?>/admin/monitor/monitor.php?time='+this.time;}
break; case 'request-waiting':
this.request = true; break; case 'request-cancelled':
this.request = false; if (!this.refreshed) { this.refreshed = true; parent.window.monitor.location.href = '<?php echo($GLOBALS['conf']['url']); ?>/admin/monitor/monitor.php?time='+this.time;}
break; case 'activity':
if (!this.request && !this.refreshed) { this.refreshed = true; parent.window.monitor.location.href = '<?php echo($GLOBALS['conf']['url']); ?>/admin/monitor/monitor.php?time='+this.time;}
break; case 'boot':
if (parent.window.monitor.location.href !== 'about:blank') { alert('<?php echo($GLOBALS['lang']['booted']); ?>'); window.close();}
break;}
}
}
if (parent.window.monitor.location.href == '<?php echo($GLOBALS['conf']['url']); ?>/admin/monitor/response.php') { this.change_status('on'); this.change_sounds('on'); parent.window.monitor.location.href = '<?php echo($GLOBALS['conf']['url']); ?>/admin/monitor/monitor.php?time='+this.time;}
window.setTimeout('Monitor.refresh();', <?php echo($GLOBALS['conf']['monitor_refresh']); ?>);}
this.refresh = function()
{ MonitorConnection1.add('monitor', ''); MonitorConnection1.add('time', Misc.epoch()); MonitorConnection1.send('<?php echo($GLOBALS['conf']['url']); ?>/admin/monitor/response.php', 'Monitor.check(variables)', 'reset');}
this.reset = function()
{ this.time = Misc.epoch(); parent.window.monitor.location.href = '<?php echo($GLOBALS['conf']['url']); ?>/admin/monitor/monitor.php?time='+this.time;}
this.accept = function(id, chatid, type)
{ this.request = false; MonitorConnection2.add('accept_'+type, ''); MonitorConnection2.add('id', id); MonitorConnection2.add('chatid', chatid); MonitorConnection2.add('time', Misc.epoch()); MonitorConnection2.send('<?php echo($GLOBALS['conf']['url']); ?>/admin/monitor/response.php', 'Monitor.reset()', 'reset'); switch (type) { case 'chat':
window.open('<?php echo($GLOBALS['conf']['url']); ?>/live/chat/main.php?admin&chatid='+chatid, 'chat_'+chatid, 'toolbar=no, status=no, scrollbars=no, resizable=yes, location=no, menubar=no, directories=no, width=<?php echo($GLOBALS['conf']['chat_op_width']); ?>, height=<?php echo($GLOBALS['conf']['chat_op_height']); ?>'); break; case 'transfer':
window.open('<?php echo($GLOBALS['conf']['url']); ?>/live/chat/main.php?admin&chatid='+chatid, 'transfer_'+chatid, 'toolbar=no, status=no, scrollbars=no, resizable=yes, location=no, menubar=no, directories=no, width=<?php echo($GLOBALS['conf']['chat_op_width']); ?>, height=<?php echo($GLOBALS['conf']['chat_op_height']); ?>'); break; case 'opchat':
window.open('<?php echo($GLOBALS['conf']['url']); ?>/live/chat/main.php?admin&opchat&chatid='+chatid, 'operator_'+chatid, 'toolbar=no, status=no, scrollbars=no, resizable=yes, location=no, menubar=no, directories=no, width=<?php echo($GLOBALS['conf']['opchat_width']); ?>, height=<?php echo($GLOBALS['conf']['opchat_height']); ?>'); break;}
}
this.decline = function(id, chatid, type)
{ this.request = false; MonitorConnection2.add('decline_'+type, ''); MonitorConnection2.add('id', id); MonitorConnection2.add('chatid', chatid); MonitorConnection2.add('time', Misc.epoch()); MonitorConnection2.send('<?php echo($GLOBALS['conf']['url']); ?>/admin/monitor/response.php', 'Monitor.reset()', 'reset');}
this.info = function(chatid)
{ window.open('<?php echo($GLOBALS['conf']['url']); ?>/admin/monitor/info.php?chatid='+chatid, 'info_'+chatid, 'toolbar=no, resizable=yes, status=no, scrollbars=yes, location=no, menubar=no, directories=no, width=<?php echo($GLOBALS['conf']['info_width']); ?>, height=<?php echo($GLOBALS['conf']['info_height']); ?>');}
this.initiate = function(chatid)
{ window.open('<?php echo($GLOBALS['conf']['url']); ?>/admin/monitor/initiate.php?chatid='+chatid, 'initiate_'+chatid, 'toolbar=no, resizable=yes, status=no, scrollbars=yes, location=no, menubar=no, directories=no, width=<?php echo($GLOBALS['conf']['initiate_width']); ?>, height=<?php echo($GLOBALS['conf']['initiate_height']); ?>');}
this.operator_chat = function()
{ this.time = Misc.epoch(); window.open('<?php echo($GLOBALS['conf']['url']); ?>/live/operator.php', 'opchat_'+this.time, 'toolbar=no, status=no, scrollbars=yes, location=no, menubar=no, directories=no, width=<?php echo($GLOBALS['conf']['opchat_width']); ?>, height=<?php echo($GLOBALS['conf']['opchat_height']); ?>');}
this.change_status = function(x, on, off)
{ this.time = Misc.epoch(); if (x == 'img') { if (parent.window.winstatus.document.getElementById('status').src == on) { parent.window.winstatus.document.getElementById('status').src = off; x = 'off';} else { parent.window.winstatus.document.getElementById('status').src = on; x = 'on';}
} else { if (x == 'on') { parent.window.winstatus.document.getElementById('status_on').className = 'selected'; parent.window.winstatus.document.getElementById('status_off').className = 'not_selected';} else { parent.window.winstatus.document.getElementById('status_on').className = 'not_selected'; parent.window.winstatus.document.getElementById('status_off').className = 'selected';}
}
MonitorConnection2.add('status', ''); MonitorConnection2.add('x', x); MonitorConnection2.add('time', Misc.epoch()); MonitorConnection2.send('<?php echo($GLOBALS['conf']['url']); ?>/admin/monitor/response.php', '', 'reset');}
this.change_sounds = function(x, on, off)
{ this.time = Misc.epoch(); if (x == 'img') { if (parent.window.winstatus.document.getElementById('sounds').src == on) { parent.window.winstatus.document.getElementById('sounds').src = off; x = 'off';} else { parent.window.winstatus.document.getElementById('sounds').src = on; x = 'on';}
} else { if (x == 'on') { parent.window.winstatus.document.getElementById('sounds_on').className = 'selected'; parent.window.winstatus.document.getElementById('sounds_off').className = 'not_selected';} else { parent.window.winstatus.document.getElementById('sounds_on').className = 'not_selected'; parent.window.winstatus.document.getElementById('sounds_off').className = 'selected';}
}
MonitorConnection2.add('sounds', ''); MonitorConnection2.add('x', x); MonitorConnection2.add('time', Misc.epoch()); MonitorConnection2.send('<?php echo($GLOBALS['conf']['url']); ?>/admin/monitor/response.php', '', 'reset');}
}
var Monitor = new Monitor(); 

    //=======================================================================
