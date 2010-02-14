<?php
    if (isset($_GET['departmentid'])) {
        $departmentid = $_GET['departmentid'];
    } else {
        $departmentid = '';
    }
    if (isset($_GET['nick'])) {
        $nick = urlencode($_GET['nick']);
    } else {
        $nick = '';
    }
?>

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

    var LiveConnection1 = new Aardvark('LiveConnection1');
    var LiveConnection2 = new Aardvark('LiveConnection2');

    function Live() {

        this.time = Misc.epoch();
        this.start = Misc.epoch();
        this.mac = navigator.platform.indexOf('Mac');
        this.chatid = '<?php echo($chatid); ?>';
        this.image_width = 0;
        this.initiate_flag = 0;
        this.status = '<?php if ($GLOBALS['live']->status_department($departmentid)) { echo('online'); } else { echo('offline'); } ?>';
        this.footprint = '&footprint=yes';
        this.refurl = document.referrer;
        this.page = document.location.toString();
        this.useragent = navigator.appName+' - '+navigator.userAgent;
        this.online_image = '<?php echo($GLOBALS['conf']['url']); ?>/live/icon.php?status=online&departmentid=<?php echo($departmentid); ?>';
        this.offline_image = '<?php echo($GLOBALS['conf']['url']); ?>/live/icon.php?status=offline&departmentid=<?php echo($departmentid); ?>';
        this.chatwin;
        this.endchat = false;


        this.initiate_accept = function()
        {
            window.open('<?php echo($GLOBALS['conf']['url']); ?>/live/request.php?initiate&chatid=<?php echo($chatid); ?>', 'initiate_<?php echo($chatid); ?>', 'toolbar=no, scrollbars=yes, status=no, location=no, menubar=no, directories=no, width=400, height=400');
            document.getElementById('div_initiate').style.visibility = 'hidden';
        }

        this.initiate_decline = function()
        {
            this.time = Misc.epoch();
            LiveConnection2.add('decline_initiate', '');
            LiveConnection2.send('<?php echo($GLOBALS['conf']['url']); ?>/live/response.php', '', 'reset');
            window.setTimeout('document.getElementById(\'div_initiate\').style.visibility = \'hidden\';', 500);
        }

        this.check = function(variables)
        {
            var response = '';
            if (LiveConnection1.vars.live_<?php echo($chatid); ?>) {
                response = LiveConnection1.vars.live_<?php echo($chatid); ?>;
                this.time = Misc.epoch();
                var i = 0;
                var max = 0;
                var action = response.split('|');
                max = action.length - 1;
                for (i = 0; i < max; ++i) {
                    switch (action[i]) {
                    case 'online':
<?php
    if (!isset($_GET["text"])) {
        if (!isset($_GET["invisible"])) {
?>
                        if (this.status == 'offline') {
                            document.getElementById('image_live').src = this.online_image;
                        }
<?php
        }
    }
?>
                        this.status = 'online';
                        break;
                    case 'offline':
<?php
    if (!isset($_GET["text"])) {
        if (!isset($_GET["invisible"])) {
?>
                        if (this.status == 'online') {
                            document.getElementById('image_live').src = this.offline_image;
                        }
<?php
        }
    }
?>
                        this.status = 'offline';
                        break;
                    case 'initiate':
                        this.launch('initiate','');
                        break;
                    case 'cobrowse':
                        parent.window.location.href = '<?php echo($GLOBALS['conf']['url']); ?>/live/cobrowse.php?changepage';
                        break;
                    }
                }

            }

            <?php if ($GLOBALS['conf']['monitor_traffic']) { ?>
            if ((this.time - this.start) < 7200000) {
                window.setTimeout('Live.tracker();', <?php echo($GLOBALS['conf']['tracker_refresh']); ?>);
            }
            <?php } ?>
        }

        this.tracker = function()
        {
            this.time = Misc.epoch();
<?php if (!isset($_GET["x"]) && !isset($_SESSION['hcl_cobrowse'])) { ?>

            <?php if (isset($_GET['cobrowse'])) { ?>
            LiveConnection1.add('cobrowse', '');
            <?php } ?>
            LiveConnection1.add('live', '');
            LiveConnection1.add('departmentid', '<?php echo($departmentid); ?>');
            LiveConnection1.add('page', this.page);
            LiveConnection1.add('refurl', this.refurl);
            LiveConnection1.add('useragent', this.useragent);
            LiveConnection1.add('time', Misc.epoch());

            if (this.end_chat) {
                LiveConnection1.add('endchat', '');
                this.endchat = false;
            }

            if (this.footprint) {
                LiveConnection1.add('footprint', '');
                this.footprint = false;
            }
    
            LiveConnection1.send('<?php echo($GLOBALS['conf']['url']); ?>/live/response.php', 'Live.check(variables)', 'reset');

<?php } ?>
        }

        this.launch = function(action, departmentid, nick)
        {
            this.time = Misc.epoch();
            if (action == 'initiate') {
                if (this.initiate_flag !== 1) {
                    if (document.getElementById('div_initiate') !== null) {
                        document.getElementById('div_initiate').style.visibility = 'visible';
                        window.setTimeout('document.getElementById(\'div_initiate\').style.visibility = \'hidden\';', <?php echo($GLOBALS['conf']['initiate_timeout']); ?>);
                        this.initiate_flag = 1;
                    }
                }
            } else {
                if (departmentid == '') {
                	if (nick == '') {
                    	this.chatwin = window.open('<?php echo($GLOBALS['conf']['url']); ?>/live/main.php', 'guest_<?php echo($chatid); ?>', 'toolbar=no, scrollbars=yes, status=no, resizable=yes, location=no, menubar=no, directories=no, width=<?php echo($GLOBALS['conf']['chat_width']); ?>, height=<?php echo($GLOBALS['conf']['chat_height']); ?>');
                	} else {
                		this.chatwin = window.open('<?php echo($GLOBALS['conf']['url']); ?>/live/main.php?nick='+nick, 'guest_<?php echo($chatid); ?>', 'toolbar=no, scrollbars=yes, status=no, resizable=yes, location=no, menubar=no, directories=no, width=<?php echo($GLOBALS['conf']['chat_width']); ?>, height=<?php echo($GLOBALS['conf']['chat_height']); ?>');
                	}
                } else {
                    if (nick == '') {
                    	this.chatwin = window.open('<?php echo($GLOBALS['conf']['url']); ?>/live/main.php?departmentid='+departmentid, 'guest_<?php echo($chatid); ?>', 'toolbar=no, scrollbars=yes, status=no, resizable=yes, location=no, menubar=no, directories=no, width=<?php echo($GLOBALS['conf']['chat_width']); ?>, height=<?php echo($GLOBALS['conf']['chat_height']); ?>');
                	} else {
                		this.chatwin = window.open('<?php echo($GLOBALS['conf']['url']); ?>/live/main.php?departmentid='+departmentid+'&nick='+nick, 'guest_<?php echo($chatid); ?>', 'toolbar=no, scrollbars=yes, status=no, resizable=yes, location=no, menubar=no, directories=no, width=<?php echo($GLOBALS['conf']['chat_width']); ?>, height=<?php echo($GLOBALS['conf']['chat_height']); ?>');
                	}
                }
                if (this.chatwin.opener == null) {
                    this.chatwin.opener = self;
                }
            }
        }

<?php
    if (!isset($_GET["invisible"])) {
        if (isset($_GET["text"])) {
?>
        if (this.status == 'online') {
            document.write("<a href=\"javascript:Live.launch('chat', '<?php echo($departmentid); ?>', '<?php echo($nick); ?>')\" onMouseOver=\"Misc.statusbar('<?php echo($GLOBALS['lang']['click_for_live_help']); ?>');return true;\" onMouseOut=\"Misc.statusbar('');return true;\"><?php echo($GLOBALS['lang']['live_text_online']); ?></a>");
        } else {
            document.write("<a href=\"javascript:Live.launch('chat', '<?php echo($departmentid); ?>', '<?php echo($nick); ?>')\" onMouseOver=\"Misc.statusbar('<?php echo($GLOBALS['lang']['click_for_live_help']); ?>');return true;\" onMouseOut=\"Misc.statusbar('');return true;\"><?php echo($GLOBALS['lang']['live_text_offline']); ?></a>");
        }
<?php } else { ?>
            document.write("<a href=\"javascript:Live.launch('chat', '<?php echo($departmentid); ?>', '<?php echo($nick); ?>')\" onMouseOver=\"Misc.statusbar('<?php echo($GLOBALS['lang']['click_for_live_help']); ?>');return true;\" onMouseOut=\"Misc.statusbar('');return true;\"><img alt=\"<?php echo($GLOBALS['lang']['click_for_live_help']); ?>\" src=\"<?php echo($GLOBALS['conf']['url']); ?>/live/icon.php?status="+this.status+"&departmentid=<?php echo($departmentid); ?>\" border=\"0\" id=\"image_live\" /></a>");
<?php
        }
    }
?>

    }

    var Live = new Live();
    setTimeout('Live.tracker();', 1000);

    //=======================================================================
