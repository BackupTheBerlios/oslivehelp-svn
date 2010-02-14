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
    // This file is used to track visitors using javascript

    var ChatConnection1 = new Aardvark('ChatConnection1');
    var ChatConnection2 = new Aardvark('ChatConnection2');

    function Chat() {

        this.time = Misc.epoch();
        this.mac = navigator.platform.indexOf('Mac');
        this.image_width = 0;
        this.typing_flag = false;
        this.focus_flag = true;
        this.exit_flag = false;
        this.sounds_flag = true;
        this.close_flag = false;
        this.save_flag = false;
        this.size = <?php echo($GLOBALS['conf']['text_size']); ?>;
        this.span = '';
        this.chat_timer = new Date();
        this.start_timer = new Date();
        this.total_time = 0;
        this.hours = 0;
        this.minutes = 0;
        this.seconds = 0;
        this.chat_message = '';
        this.left_chat = false;
        this.encode = '';
        this.chr = '';
        this.busy = false;
        this.confirm = false;
        this.guest = '<?php echo(str_replace('\'', '\\\'', $_SESSION['hcl_'.$chatid]['guest'])); ?>';
        this.operator = '<?php echo(str_replace('\'', '\\\'', $_SESSION['hcl_'.$chatid]['operator'])); ?>';
        this.department = '<?php echo(str_replace('\'', '\\\'', $_SESSION['hcl_'.$chatid]['department'])); ?>';
        this.chatid = '<?php echo($chatid); ?>';
        this.win = false;
        <?php if ($GLOBALS['cobrowse']->getpage($chatid)) { ?>
        this.cobrowse = true;
        <?php } else { ?>
        this.cobrowse = false;
        <?php } ?>
        this.coforms_current = '';
        this.checksum = 0;
        this.allow_redisplay = true;
        this.allow_refresh = true;
        this.allow_cobrowse = false;
        this.sound_n = 0;
        this.copageurl = '';

        this.check = function(variables)
        {
            var i = 0;
            var max = 0;
            var message = false;
            var typing = false;
            var operator = '';
            var department = '';
            var response = '';
            var regex = new RegExp();
            var match;
            var regex2 = new RegExp();
            var match2;

            if (ChatConnection1.vars.chat_<?php echo($chatid); ?>) {
                response = ChatConnection1.vars.chat_<?php echo($chatid); ?>;
                regex = new RegExp();
                match = regex.exec(response);
                regex2 = new RegExp();
                match2 = regex2.exec(response);

                regex = new RegExp(/\|message\:([^\|]*)\|/);
                if (match = regex.exec(response)) {
                    if (match[1]) {
                        message = true;
                        this.checksum++;
                        match[1] = match[1].replace(/\+/g, " ");
<?php if (isset($_GET['admin']) && $_SESSION['hcl_'.$chatid]['isoperator'] == 'true') { ?>
                        this.write('true', 'g', match[1]);
<?php } else { ?>
                        this.write('false', 'o', match[1]);
<?php } ?>
                    }
                }

                regex = new RegExp(/\|push\:([^\|]*)\|/);
                if (match = regex.exec(response)) {
                    if (match[1]) {
                        this.push(match[1]);
                    }
                }

                regex = new RegExp(/\|download\:([^\:]*):([^\|]*)\|/);
                if (match = regex.exec(response)) {
                    if (match[1]) {
                        this.download(unescape(match[1]), match[2]);
                    }
                }

                regex = new RegExp(/\|transfer-operator\:([^\|]*)\|/);
                if (match = regex.exec(response)) {
                    if (match[1]) {
                        operator = match[1];
                    }
                }

                regex = new RegExp(/\|transfer-department\:([^\|]*)\|/);
                if (match = regex.exec(response)) {
                    if (match[1]) {
                        department = match[1];
                    }
                }

                regex = new RegExp(/\|copage\:([^\|]*)\|/);
                if (match = regex.exec(response)) {
                    if (match[1]) {
                        this.copage(match[1]);
                    }
                }

                regex = new RegExp(/\|coforms\:([^\:]*):([^\:]*):([^\|]*)\|/);
                if (match = regex.exec(response)) {
                    if (match[1]) {
                        this.coforms(match[1], unescape(match[2]), unescape(match[3]));
                    }
                }

                regex = new RegExp(/\|comarker\:([^\:]*):([^\:]*):([^\|]*)\|/);
                if (match = regex.exec(response)) {
                    if (match[1]) {
                        this.comarker(match[1], match[2], match[3]);
                    }
                }

                var action = response.split('|');
                max = action.length - 1;
                for (i = 0; i < max; ++i) {
                    switch (action[i]) {
                    case 'typing':
                        if (!message) {
                            typing = true;
<?php if (isset($_GET['admin']) && $_SESSION['hcl_'.$chatid]['isoperator'] == 'true') { ?>
                            if (parent.window.chat_type.document.getElementById('typing').innerHTML !== this.parse_names('<?php echo($GLOBALS['lang']['typing_message']); ?>', 'guest')) {
                                parent.window.chat_type.document.getElementById('typing').innerHTML = this.parse_names('<?php echo($GLOBALS['lang']['typing_message']); ?>', 'guest');
                            }
<?php } else { ?>
                            if (parent.window.chat_type.document.getElementById('typing').innerHTML !== this.parse_names('<?php echo($GLOBALS['lang']['typing_message']); ?>', 'operator')) {
                                parent.window.chat_type.document.getElementById('typing').innerHTML = this.parse_names('<?php echo($GLOBALS['lang']['typing_message']); ?>', 'operator');
                            }
<?php } ?>
                        }
                        break;
                    case 'hold':
                        this.chat_hold();
                        break;
                    case 'resume':
                        this.chat_resume();
                        break;
                    case 'display':
                        parent.window.chat_display.document.location.href = '<?php echo($GLOBALS['conf']['url']); ?>/live/chat/display.php?chatid=<?php echo($chatid); ?><?php if (isset($_GET['admin'])) {?>&admin<?php } ?>&time='+this.time;
                        break;
                    case 'upload':
                        this.upload();
                        break;
                    case 'cobrowse':
                        this.cobrowse_started();
                        break;
                    case 'cobrowse-allow':
                        this.allow_cobrowse = true;
                        break;
                    case 'cobrowse-disallow':
                        this.allow_cobrowse = false;
                        break;
                    case 'cobrowse-disconnect':
                        if (this.cobrowse == true && parent.window.opener.CoBrowse.track == false) {
                            this.cobrowse_disconnect();
                        }
                        break;
                    case 'comarker-clear':
                        this.comarker_clear();
                        break;
                    case 'transfer-yes':
                        this.set_names(operator, department);
                        this.transfer('yes');
                        break;
                    case 'transfer-no':
                        this.transfer('no');
                        break;
                    case 'close':
                        if (!this.close_flag) {
                            this.close_window();
                        }
                        break;
                    }
                }
            }

            if (!message && !typing && parent.window.chat_type.document.getElementById('typing')) {
                parent.window.chat_type.document.getElementById('typing').innerHTML = '';
            }

            if (this.typing_flag && parent.window.chat_send.document.getElementById('chat_box').value == '') {
                this.typing_flag = false;
            }

            if (!this.close_flag) {
                window.setTimeout('parent.window.Chat.refresh();', <?php echo($GLOBALS['conf']['chat_refresh']); ?>);
            }

        }

        this.refresh = function()
        {
            <?php if (isset($_GET['admin'])) { ?>ChatConnection1.add('admin', '');<?php } ?>
            ChatConnection1.add('chat', '');
            ChatConnection1.add('chatid', '<?php echo($chatid); ?>');
            ChatConnection1.add('checksum', this.checksum);
            ChatConnection1.add('copage', this.copageurl);
            ChatConnection1.add('time', Misc.epoch());

            if (this.typing_flag) {
                ChatConnection1.add('typing', '');
            }

            ChatConnection1.send('<?php echo($GLOBALS['conf']['url']); ?>/live/chat/response.php', 'Chat.check(variables)', 'reset');
        }

        this.set_names = function(operator, department)
        {
            this.operator = operator;
            this.department = department;
        }

        this.parse_names = function(string, person)
        {
            if (person == 'operator') {
                string = string.replace('PERSON', this.operator);
            }
            if (person == 'guest') {
                string = string.replace('PERSON', this.guest);
            }
            string = string.replace('GUEST', this.guest);
            string = string.replace('OPERATOR', this.operator);
            string = string.replace('DEPARTMENT', this.department);
            return string;
        }

        this.empty = function()
        {
            alert('<?php echo($GLOBALS['lang']['chat_empty']); ?>');
            window.close();
        }

        this.font_larger = function()
        {
            this.size = this.size + 2;
            this.font_apply();
        }

        this.font_smaller = function()
        {
            this.size = this.size - 2;
            this.font_apply();
        }

        this.font_apply = function()
        {
            this.span = parent.window.chat_display.document.getElementsByTagName('span');
            for (i = 0; i < this.span.length; i++) {
                this.span[i].style.fontSize = this.size + 'px';
            }
            parent.window.chat_display.scroll(0,10000000);
        }

        this.focus = function(x, on, off)
        {
            this.time = Misc.epoch();
            if (x == 'img') {
                if (parent.window.chat_top.document.getElementById('focus').src == on) {
                    parent.window.chat_top.document.getElementById('focus').src = off;
                    this.focus_flag = false;
                } else {
                    parent.window.chat_top.document.getElementById('focus').src = on;
                    this.focus_flag = true;
                }
            } else {
                if (x == 'on') {
                    this.focus_flag = true;
                    parent.window.chat_top.document.getElementById('focus_on').className = 'selected';
                    parent.window.chat_top.document.getElementById('focus_off').className = 'not_selected';
                }
                if (x == 'off') {
                    this.focus_flag = false;
                    parent.window.chat_top.document.getElementById('focus_on').className = 'not_selected';
                    parent.window.chat_top.document.getElementById('focus_off').className = 'selected';
                }
            }
        }

        this.sounds = function(x, on, off)
        {
            if (x == 'img') {
                if (parent.window.chat_top.document.getElementById('sounds').src == on) {
                    parent.window.chat_top.document.getElementById('sounds').src = off;
                    this.sounds_flag = false;
                } else {
                    parent.window.chat_top.document.getElementById('sounds').src = on;
                    this.sounds_flag = true;
                }
            } else {
                if (x == 'on') {
                    this.sounds_flag = true;
                    parent.window.chat_top.document.getElementById('sounds_on').className = 'selected';
                    parent.window.chat_top.document.getElementById('sounds_off').className = 'not_selected';
                }
                if (x == 'off') {
                    this.sounds_flag = false;
                    parent.window.chat_top.document.getElementById('sounds_on').className = 'not_selected';
                    parent.window.chat_top.document.getElementById('sounds_off').className = 'selected';
                }
            }
        }

        this.timer = function()
        {
            this.chat_timer = new Date();
            this.total_time = this.chat_timer.getTime() - this.start_timer.getTime();
            this.chat_timer.setTime(this.total_time);
            if (this.chat_timer.getMinutes() < 10) {
                this.minutes = '0'+this.chat_timer.getMinutes();
            } else {
                this.minutes = this.chat_timer.getMinutes();
            }
            if (this.chat_timer.getSeconds() < 10) {
                this.seconds = '0'+this.chat_timer.getSeconds();
            } else {
                this.seconds = this.chat_timer.getSeconds();
            }
            if (this.minutes < 60) {
                parent.window.chat_top.document.getElementById('time').innerHTML = this.minutes+':'+this.seconds;
            } else {
                this.hours = Math.floor(this.minutes / 60);
                this.minutes = this.minutes - (this.hours * 60);
                parent.window.chat_top.document.getElementById('time').innerHTML = this.hours+':'+this.minutes+':'+this.seconds;
            }
            setTimeout('parent.window.Chat.timer()', 1000);
        }

        this.save = function()
        {
            if (parent.window.opener.CoBrowse) {
                parent.window.opener.CoBrowse.track = false;
            }
            if (!this.save_flag) {
                this.save_flag = true;
<?php if (isset($_GET['admin']) && $_SESSION['hcl_'.$chatid]['isoperator'] == 'true' && !$GLOBALS['operator']->autosave_transcripts() && !isset($_GET['opchat'])) { ?>
                this.time = Misc.epoch();
                this.exit_flag = confirm('<?php echo($GLOBALS['lang']['save_transcript']); ?>');
                if (this.exit_flag) {
                    parent.window.location.href = '<?php echo($GLOBALS['conf']['url']); ?>/live/chat/end.php?save<?php if (isset($_GET['admin'])) {?>&admin<?php } ?>&chatid=<?php echo($chatid); ?>&time='+this.time;
                } else {
                    parent.window.location.href = '<?php echo($GLOBALS['conf']['url']); ?>/live/chat/end.php?chatid=<?php echo($chatid); ?><?php if (isset($_GET['admin'])) {?>&admin<?php } ?>&time='+this.time;
                }
<?php } ?>
            } else {
                return true;
            }
<?php if (!isset($_GET['admin'])) { ?>
            if (parent.window.opener.Live) {
                parent.window.opener.Live.end_chat = true;
            } else {
                parent.window.location.href = '<?php echo($GLOBALS['conf']['url']); ?>/live/chat/end.php?chatid=<?php echo($chatid); ?><?php if (isset($_GET['admin'])) {?>&admin<?php } ?><?php if (isset($_GET['opchat'])) {?>&opchat<?php } ?>&time='+this.time;
            }
<?php } ?>
        }

        this.push = function(page)
        {
            this.win = window.open(page, 'chat_push', 'toolbar=yes, location=yes, status=yes, menubar=yes, scrollbars=yes, resizable=yes');
            if (!this.win) {
                this.confirm = confirm('<?php echo($GLOBALS['lang']['popup_blocker_detected']); ?>');
                if (this.confirm) {
                    setTimeout('Chat.push(page);', 10000);
                } else {
                    return false;
                }
            }
        }

        this.upload = function()
        {
            this.time = Misc.epoch();
            this.win = window.open('<?php echo($GLOBALS['conf']['url']); ?>/live/chat/upload.php?chatid=<?php echo($chatid); ?><?php if (isset($_GET['admin'])) {?>&admin<?php } ?>&time='+this.time, 'chat_upload', 'toolbar=no, location=no, status=yes, menubar=no, scrollbars=yes, resizable=yes, width=400, height=200');
            if (!this.win) {
                this.confirm = confirm('<?php echo($GLOBALS['lang']['popup_blocker_detected']); ?>');
                if (this.confirm) {
                    setTimeout('Chat.upload();', 5000);
                } else {
                    return false;
                }
            }
        }

        this.download = function(size, id)
        {
            this.time = Misc.epoch();
            parent.window.chat_download.location.href = '<?php echo($GLOBALS['conf']['url']); ?>/live/chat/download.php?chatid=<?php echo($chatid); ?>&id='+id+'<?php if (isset($_GET['admin'])) {?>&admin<?php } ?>&time='+this.time;
            message = '<br /><div align=\"center\"><i><span class=\"operator\"><?php echo($GLOBALS['lang']['transferring_file']); ?> ('+size+')</span></i></div><br /><br />\n';
            parent.window.chat_display.document.body.innerHTML = parent.window.chat_display.document.body.innerHTML + message;
            this.font_apply();
        }

        this.chat_hold = function()
        {
            this.chat_message = '<br /><div align="center"><i><span class="operator">'+this.parse_names('<?php echo($GLOBALS['lang']['chat_hold']); ?>', '')+'</span></i></div><br />\n';
            parent.window.chat_display.document.body.innerHTML = parent.window.chat_display.document.body.innerHTML + this.chat_message;
            this.font_apply();
        }

        this.chat_resume = function()
        {
            this.chat_message = '<br /><div align="center"><i><span class="operator">'+this.parse_names('<?php echo($GLOBALS['lang']['chat_resume']); ?>', '')+'</span></i></div><br />\n';
            parent.window.chat_display.document.body.innerHTML = parent.window.chat_display.document.body.innerHTML + this.chat_message;
            this.font_apply();
        }

        this.transfer = function(x)
        {
            this.time = Misc.epoch();
<?php if (isset($_GET['admin']) && $_SESSION['hcl_'.$chatid]['isoperator'] == 'true') { ?>
            if (x == 'yes') {
                alert(this.parse_names('<?php echo($GLOBALS['lang']['transferred']); ?>', ''));
                parent.window.close();
            } else {
                this.chat_message = '<br /><div align="center"><i><span class="operator"><?php echo($GLOBALS['lang']['transfer_failed']); ?></span></i></div><br /><br />\n';
                parent.window.chat_display.document.body.innerHTML = parent.window.chat_display.document.body.innerHTML + this.chat_message;
                this.font_apply();
            }
<?php } else { ?>
            this.chat_message = '<br /><div align="center"><i><span class="operator">'+this.parse_names('<?php echo($GLOBALS['lang']['transferred']); ?>', '')+'</span></i></div><br />\n';
            parent.window.chat_display.document.body.innerHTML = parent.window.chat_display.document.body.innerHTML + this.chat_message;
            this.font_apply();
            parent.window.chat_top.location.href = '<?php echo($GLOBALS['conf']['url']); ?>/live/chat/top.php?chatid=<?php echo($chatid); ?><?php if (isset($_GET['admin'])) { ?>&admin<?php } ?>&time='+this.time;
<?php } ?>
        }

        this.close_window = function()
        {
            this.time = Misc.epoch();
            if (!this.close_flag) {
                this.close_flag = true;
<?php if (isset($_GET['admin']) && $_SESSION['hcl_'.$chatid]['isoperator'] == 'true' && !isset($_GET['opchat'])) { ?>
                this.left_chat = confirm(this.parse_names('<?php echo($GLOBALS['lang']['left_chat']); ?>', '')+'\n<?php echo($GLOBALS['lang']['close_window']); ?>');
                if (this.left_chat) {
<?php   if (!$GLOBALS['operator']->autosave_transcripts()) { ?>
                    this.save();
<?php   } else { ?>
                    parent.window.location.href = '<?php echo($GLOBALS['conf']['url']); ?>/live/chat/end.php?chatid=<?php echo($chatid); ?><?php if (isset($_GET['admin'])) {?>&admin<?php } ?>&time='+this.time;
<?php   } ?>
                } else if (parent.window.chat_send.document.getElementById('chat_box')) {
                    parent.window.chat_send.document.getElementById('chat_box').disabled = true;
                }
<?php } else { ?>
                parent.window.location.href = '<?php echo($GLOBALS['conf']['url']); ?>/live/chat/end.php?chatid=<?php echo($chatid); ?><?php if (isset($_GET['admin'])) {?>&admin<?php } ?><?php if (isset($_GET['opchat'])) {?>&opchat<?php } ?>&time='+this.time;
<?php } ?>
            }
        }

        this.write = function(admin, x, message)
        {
            if (x == 'o') {
                this.chat_message = '<span class="operator"><b>'+this.operator+':</b> '+message+'</span><br />\n';
            } else {
                this.chat_message = '<span class="guest"><b>'+this.guest+':</b> '+message+'</span><br />\n';
            }
            if ((x == 'o' && admin == 'false') || (x == 'g' && admin == 'true')) {
                parent.window.chat_display.document.body.innerHTML = parent.window.chat_display.document.body.innerHTML + this.chat_message;
                this.font_apply();
                if (this.sounds_flag) {
                    if (this.mac > -1) {
                        parent.window.chat_type.document.getElementById('typing').innerHTML = '<embed src="<?php echo($GLOBALS['conf']['url']); ?>/templates/<?php echo($GLOBALS['conf']['template']); ?>/sounds/ding.mp3" id="message_sound" loop="false" autostart="true" hidden="true"></embed>';
                    } else {
                        parent.window.chat_type.document.getElementById('typing').innerHTML = '<object height="0" width="0" classid="clsid:22D6F312-B0F6-11D0-94AB-0080C74C7E95"><param name="AutoStart" value="1" /><param name="FileName" value="<?php echo($GLOBALS['conf']['url']); ?>/templates/<?php echo($GLOBALS['conf']['template']); ?>/sounds/ding.mp3" /></object>';
                    }
                } else {
                    parent.window.chat_type.document.getElementById('typing').innerHTML = '';
                }
                this.focus_chat();
            }
        }

        this.message = function()
        {
            this.time = Misc.epoch();
            if (parent.window.chat_send.document.getElementById('chat_box').value !== '') {
                this.chat_message = '';
                this.chat_message = parent.window.chat_send.document.getElementById('chat_box').value;
                this.send_message();
            }
            return false;
        }

        this.typed = function()
        {
            this.typing_flag = false;
            this.focus_chat();
        }

        this.typing = function()
        {
            if (parent.window.chat_send.document.getElementById('chat_box').value !== '') {
                this.typing_flag = true;
            } else {
                this.typing_flag = false;
            }
        }

        this.focus_chat = function()
        {
            if (this.focus_flag) {
                if (parent.window.chat_send.document.getElementById('chat_box')) {
                    parent.window.chat_send.document.getElementById('chat_box').focus();
                }
            }
        }

        this.disabled = function()
        {
            if (parent.window.chat_send.document.getElementById('chat_box').disabled) {
                return true;
            } else {
                return false;
            }
        }

        this.insert = function(arg)
        {
            switch (arg) {
                case 'url':
                    if (!this.disabled()) {
                        parent.window.chat_send.document.getElementById('chat_box').value = parent.window.chat_send.document.getElementById('chat_box').value+'url:http://';
                        this.focus_chat();
                    }
                    break;
                case 'image':
                    if (!this.disabled()) {
                        parent.window.chat_send.document.getElementById('chat_box').value = parent.window.chat_send.document.getElementById('chat_box').value+'image:http://';
                        this.focus_chat();
                    }
                    break;
                case 'push':
                    if (!this.disabled()) {
                        parent.window.chat_send.document.getElementById('chat_box').value = parent.window.chat_send.document.getElementById('chat_box').value+'push:http://';
                        this.focus_chat();
                    }
                    break;
                case 'nick':
                    if (!this.disabled()) {
                        parent.window.chat_send.document.getElementById('chat_box').value = parent.window.chat_send.document.getElementById('chat_box').value+'%%user%%';
                        this.focus_chat();
                    }
                    break;
                case 'email':
                    if (!this.disabled()) {
                        parent.window.chat_send.document.getElementById('chat_box').value = parent.window.chat_send.document.getElementById('chat_box').value+'email:';
                        this.focus_chat();
                    }
                    break;
            }
        }

        this.transfer_chat = function(operatorid, departmentid)
        {
            this.time = Misc.epoch();
            this.confirm = confirm('<?php echo($GLOBALS['lang']['confirm_transfer']); ?>');
            if (this.confirm) {
                <?php if (isset($_GET['admin'])) { ?>ChatConnection2.add('admin', '');<?php } ?>
                ChatConnection2.add('transfer', '');
                ChatConnection2.add('chatid', this.chatid);
                ChatConnection2.add('operatorid', operatorid);
                ChatConnection2.add('departmentid', departmentid);
                ChatConnection2.add('time', Misc.epoch());
                ChatConnection2.send('<?php echo($GLOBALS['conf']['url']); ?>/live/chat/response.php', '', 'reset');
            } else {
                return false;
            }
        }

        this.cobrowse_started = function()
        {
            if (!this.cobrowse && this.allow_cobrowse) {
                if (parent.window.opener.CoBrowse) {
                    this.cobrowse = true;
                    ChatConnection2.add('cobrowsestarted', '');
                    ChatConnection2.add('chatid', this.chatid);
                    ChatConnection2.add('time', Misc.epoch());
                    ChatConnection2.send('<?php echo($GLOBALS['conf']['url']); ?>/live/chat/response.php', '', 'reset');
                    this.chat_message = '<br /><div align="center"><i><span class="operator"><?php echo(parse_with_session($chatid, $GLOBALS['lang']['cobrowse_started'])); ?></span></i></div><br />';
                    parent.window.chat_display.document.body.innerHTML = parent.window.chat_display.document.body.innerHTML + this.chat_message;
                    this.font_apply();
                    parent.window.opener.CoBrowse.track = true;
                    parent.window.opener.CoBrowse.initiateforms();
                }
            }
        }

        this.cobrowse_disconnect = function()
        {
            this.cobrowse = false;
            this.chat_message = '<br /><div align="center"><i><span class="operator"><?php echo($GLOBALS['lang']['cobrowse_ended']); ?></span></i></div><br />';
            parent.window.chat_display.document.body.innerHTML = parent.window.chat_display.document.body.innerHTML + this.chat_message;
            this.font_apply();
        }

        this.coforms = function(type, name, value)
        {
            if (this.cobrowse) {
                if (parent.window.opener.CoBrowse.track) {
                    parent.window.opener.CoBrowse.getforms(type, name, value);
                    if (name !== this.coforms_current) {
                        this.coforms_current = name;
                        this.chat_message = '<br /><div align="center"><i><span class="operator"><?php echo(parse_with_session($chatid, $GLOBALS['lang']['coforms_received'])); ?></span></i></div><br />';
                        parent.window.chat_display.document.body.innerHTML = parent.window.chat_display.document.body.innerHTML + this.chat_message;
                        this.font_apply();
                    }
                }
            }
        }

        this.copage = function(url)
        {
            if (this.cobrowse) {
                if (parent.window.opener) {
                    if (url != this.copageurl) {
                        this.copageurl = url;
                        parent.window.opener.document.location = unescape(url);
                    }
                }
            }
        }

        this.comarker = function(type, x, y)
        {
            if (this.cobrowse) {
                if (parent.window.opener.CoBrowse.track) {
                    parent.window.opener.CoBrowse.insertmarker(type, x, y);
                }
            }
        }

        this.comarker_clear = function()
        {
            if (this.cobrowse) {
                if (parent.window.opener.CoBrowse.track) {
                    parent.window.opener.CoBrowse.clearmarkers();
                }
            }
        }

        this.file = function(size)
        {
            this.chat_message = '<br /><div align="center"><i><span class="operator"><?php echo($GLOBALS['lang']['transferring_file']); ?> ('+size+')</span></i></div><br />';
            parent.window.chat_display.document.body.innerHTML = parent.window.chat_display.document.body.innerHTML + this.chat_message;
            this.font_apply();
        }

        this.disable_send = function()
        {
            parent.window.chat_send.document.getElementById('chat_submit').disabled = true;
            parent.window.chat_send.document.getElementById('chat_box').value = '';
        }
        
        this.enable_send = function()
        {
            parent.window.chat_send.document.getElementById('chat_submit').disabled = false;
            this.typed();
        }

        this.send_message = function()
        {
            this.disable_send();
            this.time = Misc.epoch();
            this.encode = '';
            
            if (this.chat_message.length > 300) {
                alert('<?php echo($GLOBALS['lang']['str_too_long']); ?>');
                this.chat_message = this.chat_message.substring(0,300);
                parent.window.chat_send.document.getElementById('chat_box').value = this.chat_message.substring(300, (this.chat_message.length - 300));
            }

            for (var i = 0; i < this.chat_message.length; ++i) {
                this.chr = this.chat_message.charCodeAt(i);
                this.encode += '&#' + this.chr + ';';
            }
<?php if (isset($_GET['admin'])) { ?>
            this.chat_message = this.chat_message + ' ';
            this.chat_message = this.chat_message.replace(/</g, "&lt;");
            this.chat_message = this.chat_message.replace(/>/g, "&gt;");
            this.chat_message = this.chat_message.replace(/%%user%%/ig, this.guest);
            this.chat_message = this.chat_message.replace(/url:(.*?) /ig, "<a href=\"$1\" target=\"_blank\">$1</a> ");
            this.chat_message = this.chat_message.replace(/image:(.*?) /ig, "<img src=\"$1\" alt=\"\" /> ");
            this.chat_message = this.chat_message.replace(/email:(.*?) /ig, "<a href=\"$1\">$1</a> ");
            this.chat_message = this.chat_message.replace(/push:(.*?) /ig, "<i><?php echo($GLOBALS['lang']['pushed_page']); ?>: <a href=\"$1\" target=\"_blank\">$1</a></i> ");
            this.chat_message = '<span class="operator"><b>'+this.operator+':</b> '+this.chat_message+'</span><br />';
<?php } else { ?>
            this.chat_message = this.chat_message.replace(/</g, "&lt;");
            this.chat_message = this.chat_message.replace(/>/g, "&gt;");
            this.chat_message = '<span class="guest"><b>'+this.guest+':</b> '+this.chat_message+'</span><br />';
<?php } ?>

            parent.window.chat_display.document.body.innerHTML = parent.window.chat_display.document.body.innerHTML + this.chat_message;
            this.font_apply();

            <?php if (isset($_GET['admin'])) { ?>ChatConnection2.add('admin', '');<?php } ?>
            <?php if (isset($_GET['opchat'])) { ?>ChatConnection2.add('opchat', '');<?php } ?>
            ChatConnection2.add('send', '');
            ChatConnection2.add('chatid', this.chatid);
            ChatConnection2.add('message', this.encode);
            ChatConnection2.add('time', Misc.epoch());
            ChatConnection2.send('<?php echo($GLOBALS['conf']['url']); ?>/live/chat/response.php', 'Chat.enable_send()', 'reset');

            this.checksum++;

        }

        this.canned = function(msg)
        {
            this.time = Misc.epoch();
            this.chat_message = '';
            this.chat_message = msg;
            this.send_message();
        }

    }

    var Chat = new Chat();

    //=======================================================================
